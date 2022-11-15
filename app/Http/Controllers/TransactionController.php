<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\AlmostOutOfStock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index(): mixed
    {
        $categories = Category::all();

        $tenRecentPurchases = Transaction::where('type', '=', Transaction::TYPE['purchase'])
            ->with('product')
            ->with('salesPriceDuringTransaction')
            ->with('purchasePriceDuringTransaction')
            ->latest('created_at')
            ->take(5)
            ->get();

        $tenRecentSales = Transaction::where('type', '=', Transaction::TYPE['sales'])
            ->with('product')
            ->with('salesPriceDuringTransaction')
            ->with('purchasePriceDuringTransaction')
            ->latest('created_at')
            ->take(5)
            ->get();

        $dropdownOptions = $this->dropdownOptions();

        return view('transactions.index')
            ->with(
                compact(
                    'categories',
                    'tenRecentSales',
                    'tenRecentPurchases',
                    'dropdownOptions'
                )
            );
    }

    /**
     * Displays yesterday's purchases, sales or all transaction based on param
     *
     * @param string $type
     *
     * @return View
     */
    public function yesterdaysTransactions(string $type = ''): View
    {
        $transactions = Transaction::with('product:id,name')
            ->with('purchasePriceDuringTransaction:id,value')
            ->with('salesPriceDuringTransaction:id,value')
            ->whereDate('created_at', Carbon::yesterday())
            ->when(
                !empty($type)
                and in_array(ucfirst($type), Transaction::TYPE),
                function ($transaction) use ($type) {
                    return $transaction->where('type', $type);
                }
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transactions.yesterday')->with(compact('transactions', 'type'));
    }

    /**
     * Displays any month's purchases, sales or all transaction based on param
     *
     * @param int $month
     * @param string $type
     *
     * @return View
     */
    public function monthlyTransactions(int $month, string $type = ''): View
    {
        $transactions    = Transaction::with('product:id,name')
            ->with('purchasePriceDuringTransaction:id,value')
            ->with('salesPriceDuringTransaction:id,value')
            ->whereMonth('created_at', $month)
            ->when(
                !empty($type),
                function ($q) use ($type) {
                    return $q->where('type', $type);
                }
            )
            ->orderBy('created_at', 'desc')
            ->paginate(15) ?? '';
        $year            = Carbon::now()->format('Y');
        $activeMonth     = $month;
        $activeMonthText = Carbon::create($year, $month)->format('M');

        return view('transactions.monthly')->with(
            compact(
                'transactions',
                'type',
                'year',
                'activeMonth',
                'activeMonthText',
            )
        );
    }

    /**
     * Displays any year's purchases, sales or all transaction based on param
     *
     * @param int $year
     * @param string $type
     *
     * @return View
     */
    public function yearlyTransactions(int $year, string $type = ''): View
    {
        $transactions = Transaction::with('product:id,name')
            ->with('purchasePriceDuringTransaction:id,value')
            ->with('salesPriceDuringTransaction:id,value')
            ->whereYear('created_at', $year)
            ->when(
                !empty($type),
                function ($q) use ($type) {
                    return $q->where('type', $type);
                }
            )
            ->orderBy('created_at', 'desc')
            ->paginate(15) ?? '';

        $oldestTransaction = Transaction::oldest()->first('created_at');
        $oldestYear        = $oldestTransaction->created_at->format('Y');

        return view('transactions.annual')->with(
            compact(
                'transactions',
                'type',
                'year',
                'oldestYear'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create(): void
    {
    }

    /**
     * Check transaction type
     * Check if change in purchase price or sales price with respect to latest of both
     * Update the latest price if needed
     * Update product quantity
     * Save transaction
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $product = Product::with('latestPurchasePrice')->with('latestSalesPrice')->find($request['productId']);

        $transactionType = $request['transactionType'];

        $transaction                    = new Transaction();
        $transaction->type              = $transactionType == 1
            ? $transaction::TYPE['purchase']
            : $transaction::TYPE['sales'];
        $transaction->user_id           = Auth::user()->id;
        $transaction->product_id        = $product->id;
        $transaction->sales_price_id    = $product->latestSalesPrice->id;
        $transaction->purchase_price_id = $product->latestPurchasePrice->id;
        $transaction->discount          = $request['discount'] ?? $product->discount;

        $latest     = 'latestPurchasePrice';
        $class      = 'App\Models\PurchasePrice';
        $price      = 'purchasePrice';
        $quantity   = 'purchaseQuantity';
        $modifier   = 1;
        $foreignKey = 'purchase_price_id';
        $discount   = 0;

        if ($request['transactionType'] == 2) {
            if ($request['salesQuantity'] > $product->quantity) {
                session()->flash('warning', "Cannot make sales of $request->salesQuantity items. Check stock.");

                return back();
            }

            $latest     = 'latestSalesPrice';
            $class      = 'App\Models\SalesPrice';
            $price      = 'salesPrice';
            $quantity   = 'salesQuantity';
            $modifier   = -1;
            $foreignKey = 'sales_price_id';
            $discount   = $transaction->discount;
        }


        try {
            $product->quantity = $product->quantity + ($modifier * $request[$quantity]);

            if ($product->$latest->value != $request[$price]) {
                $object             = new $class();
                $object->product_id = $request['productId'];
                $object->value      = $request[$price];
                $object->save();

                $product->$latest->id = $object->id;
            }

            $transaction->$foreignKey = $product->$latest->id;
            $transaction->quantity    = $request[$quantity];
            $transaction->discount    = $discount;
            $transaction->save();

            $product->save();

            if ($product->quantity <= AlmostOutOfStock::MINIMUM_COUNT) {
                $users = User::all();
                Notification::send($users, new AlmostOutOfStock($product));
            }

            session()->flash('success', 'Transaction entry made.');
        } catch (Exception $e) {
            session()->flash('error', 'Something went wrong. Transaction entry not made.');
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param Transaction $transaction
     *
     * @return View
     */
    public function show(Transaction $transaction): View
    {
        $transaction->load('user', 'product.category', 'salesPriceDuringTransaction', 'purchasePriceDuringTransaction');

        return view('transactions.transaction')->with(compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(): Response
    {
        //
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, $id): Response
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id): Response
    {
        //
    }


    /**
     * Create and Show PDF
     *
     * @param Transaction $transaction
     *
     * @return Response
     */
    public function createPDF(Transaction $transaction): Response
    {
        abort_if(empty($transaction), 404);
        $transaction->load(
            'user',
            'product.category',
            'salesPriceDuringTransaction',
            'purchasePriceDuringTransaction'
        );
        view()->share('transaction', $transaction);
        $pdf = Pdf::loadView('transactions.transaction_pdf');

        return $pdf->stream();
    }

    /**
     * @param string $type
     *
     * @return view
     */
    public function showTransactions(string $type): view
    {
        $type         = ucfirst($type);
        $transactions = Transaction::with('product')
            ->where('type', '=', $type)
            ->latest()
            ->paginate(10);
        $type         = $type . "s";

        return view('transactions.typed_transactions')->with(compact('transactions', 'type'));
    }

    /**
     * Returns Categories as json for Transaction, make transaction modal
     * For ajax call
     *
     *
     * @param int $categoryId
     *
     * @return JsonResponse
     */
    public function categoryProducts(int $categoryId): JsonResponse
    {
        $products = Product::where('category_id', '=', $categoryId)
            ->get(['id', 'name']);

        return response()->json(array('msg' => $products, 200));
    }

    /**
     * Dropdown options to navigate Transaction views
     * Return array with array of dropdown options where,
     * Key = dropdown menu option
     * Value = route to said menu option
     *
     * @return array[]
     */
    public function dropdownOptions(): array
    {
        return array(
            //Dropdown option for yesterday
            'yesterday' => array(
                'all'       => route('yesterdays-transactions'),
                'purchases' => route('yesterdays-transactions', array('type' => 'purchase')),
                'sales'     => route('yesterdays-transactions', array('type' => 'sale')),
            ),
            //Dropdown option for monthly
            'monthly'   => array(
                'all'       => route(
                    'monthly-transactions',
                    array(
                        'month' => Carbon::now()->format('m'),
                    )
                ),
                'purchases' => route(
                    'monthly-transactions',
                    array(
                        'month' => Carbon::now()->format('m'),
                        'type'  => 'purchase'
                    )
                ),
                'sale'      => route(
                    'monthly-transactions',
                    array(
                        'month' => Carbon::now()->format('m'),
                        'type'  => 'sale'
                    )
                ),
            ),
            //Dropdown option for yearly
            'yearly'    => array(
                'all'       => route(
                    'yearly-transactions',
                    array(
                        'year' => Carbon::now()->format('Y'),
                    )
                ),
                'purchases' => route(
                    'yearly-transactions',
                    array(
                        'year' => Carbon::now()->format('Y'),
                        'type' => 'purchase'
                    )
                ),
                'sales'     => route(
                    'yearly-transactions',
                    array(
                        'year' => Carbon::now()->format('Y'),
                        'type' => 'sale'
                    )
                ),
            ),
        );
    }
}
