<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\PurchasePrice;
use App\Models\SalesPrice;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $searchKeyword = $request['search-field'] ?? '';

        $products = Product::with('category')
            ->when(
                !empty($searchKeyword),
                function ($products) use ($searchKeyword) {
                    return $products->where('name', 'like', "%$searchKeyword%")
                        ->orWhere('description', 'like', "%$searchKeyword%");
                }
            )
            ->paginate(10);

        return view('products.index')->with(compact('products', 'searchKeyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('products.add')->with(['categories' => Category::get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $product                = new Product();
            $product->category_id   = $request['category'];
            $product->registered_by = Auth::id();
            $product->quantity      = $request['quantity'];
            $product->discount      = $request['discount'];
            $product->name          = $request['name'];
            $product->description   = $request['description'];


            $file = $request->file('productImage') ?? '';

            if ($file) {
                $newFilename = $request->productImage->getClientOriginalName();
                $file->storeAs('public/images', $newFilename);
                $product->image = $newFilename;
            }

            $product->save();

            $purchasePrice             = new PurchasePrice();
            $purchasePrice->product_id = $product->id;
            $purchasePrice->value      = $request['purchasePrice'];
            $purchasePrice->save();

            $salesPrice             = new SalesPrice();
            $salesPrice->product_id = $product->id;
            $salesPrice->value      = $request['salesPrice'];
            $salesPrice->save();

            $transaction                    = new Transaction();
            $transaction->user_id           = Auth::id();
            $transaction->product_id        = $product->id;
            $transaction->sales_price_id    = $salesPrice->id;
            $transaction->purchase_price_id = $purchasePrice->id;
            $transaction->type              = $transaction::TYPE['purchase'];
            $transaction->quantity          = $request['quantity'];
            $transaction->discount          = $request['discount'];

            $transaction->save();
            session()->flash('success', 'Product added.');
        } catch (Exception $e) {
            session()->flash('warning', 'Something went wrong. Try again.');
        }


        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     *
     * @return View
     */
    public function show($id): View
    {
        $product = Product::with('registrant')->with('category')->find($id);

        return view('products.product')->with(compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     *
     * @return View
     */
    public function edit(Product $product): View
    {
        $categories = Category::get();

        return view('products.edit')->with(compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        //Request Validation goes

        if ($request['transactionType']) {
            $class                 = $request['transactionType'] == Transaction::TYPE['purchase']
                ? 'App\Models\PurchasePrice'
                : 'App\Models\SalesPrice';
            $changedColumn         = $request['transactionType'] == Transaction::TYPE['purchase']
                ? 'purchase_price_id'
                : 'sales_price_id';
            $unchangedColumn       = $request['transactionType'] == Transaction::TYPE['purchase']
                ? 'sales_price_id'
                : 'purchase_price_id';
            $latestUnchangedColumn = $request['transactionType'] == Transaction::TYPE['purchase']
                ? 'latestSalesPrice'
                : 'latestPurchasePrice';

            $changes = abs($request->quantity - $product->quantity);

            $product->load($latestUnchangedColumn);

            $newChangedColumnValue             = new $class();
            $newChangedColumnValue->product_id = $product->id;
            $newChangedColumnValue->value      = $request['price'];
            $newChangedColumnValue->save();

            $transaction                   = new Transaction();
            $transaction->user_id          = Auth::id();
            $transaction->product_id       = $product->id;
            $transaction->$changedColumn   = $newChangedColumnValue->id;
            $transaction->$unchangedColumn = $product->$latestUnchangedColumn->id;
            $transaction->type             = $request['transactionType'];
            $transaction->quantity         = $changes;
            $transaction->discount         = $request['discount'] ?? 0;

            $transaction->save();
        }

        $product->category_id = $request['category_id'];
        $product->quantity    = $request['quantity'];
        $product->discount    = $request['discount'] ?? $product->discount;
        $product->name        = $request['name'];
        $product->description = $request['description'];


        try {
            $file = $request->file('productImage') ?? '';

            if ($file) {
                $newFilename = $request->productImage->getClientOriginalName();
                $file->storeAs('public/images', $newFilename);
                $product->image = $newFilename;
            }

            $product->update();
            session()->flash('success', "Product info updated.");
        } catch (Exception $e) {
            session()->flash('warning', "Something went wrong.");
        }

        return back();
    }


    /**
     * Returns products.trashed view with products where deleted_at exists
     *
     * @param Request $request
     *
     * @return View
     */
    public function showTrash(Request $request): View
    {
        $searchKeyword = $request['search-field'] ?? '';

        if (empty($searchKeyword)) {
            $products = Product::onlyTrashed()->paginate(10);
        } else {
            $products = Product::onlyTrashed()
                ->where('products.name', 'LIKE', "%$searchKeyword%")
                ->paginate(10);
        }

        return view('products.trashed')->with(compact('products', 'searchKeyword'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     *
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            $product->delete();
            session()->flash('warning', 'Product moved to trash.');
        } catch (Exception $e) {
            session()->flash('warning', 'Something went wrong. Try Again.');
        }

        return redirect()->back();
    }

    /**
     * Restore trashed product
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function restore(int $id): RedirectResponse
    {
        try {
            $product = Product::withTrashed()->find($id);
            $product->restore();
            session()->flash('success', "Product restored.");
        } catch (Exception $e) {
            session()->flash('warning', 'Something went wrong.');
        }

        return redirect()->back();
    }


    /**
     * Remove product from db
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function hardDelete(int $id): RedirectResponse
    {
        try {
            $product = Product::withTrashed()->find($id);
            $product->forceDelete();
            session()->flash('error', 'Product record destroyed.');
        } catch (Exception $e) {
            session()->flash('warning', "Something went wrong. Try again.");
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function filterProducts(Request $request): mixed
    {
        return $this->filteredProducts($request['filterParams']);
    }

    /**
     * Returns Products after applying filter param
     *
     * @param array $filterParams
     *
     * @return mixed
     */
    public function filteredProducts(array $filterParams): mixed
    {
        $startFormat     = 'Y-m-d';
        $startDateString = implode(
            '-',
            [$filterParams['startYear'], $filterParams['startMonth'], $filterParams['startDay']]
        );

        if (isZero($filterParams['startYear'])) {
            $startDateString = implode('-', [$filterParams['startMonth'], $filterParams['startDay']]);
            $startFormat     = 'm-d';
        }
        $startDate = Carbon::createFromFormat($startFormat, $startDateString)->startOfDay();

        $endFormat     = 'Y-m-d';
        $endDateString = implode('-', [$filterParams['endYear'], $filterParams['endMonth'], $filterParams['endDay']]);

        if (isZero($filterParams['endYear'])) {
            $endDateString = implode('-', [$filterParams['endMonth'], $filterParams['endDay']]);
            $endFormat     = 'm-d';
        }
        $endDate = Carbon::createFromFormat($endFormat, $endDateString)->endOfDay();

        try {
            $products = Product::with('category');

            $products = $filterParams['quantity'] > 0
                ? $products->where('quantity', '=', $filterParams['quantity'])
                : $products;

            if (!empty($filterParams['category_ids'])) {
                $products = $products->whereIn('category_id', $filterParams['category_ids']);
            }

            switch ([$startDateString == '0-0', $endDateString == '0-0']) {
                case [false, true]:
                    $products = $products->whereMonth('created_at', '>=', $startDate);
                    break;
                case [true, false]:
                    $products = $products->whereMonth('created_at', '<=', $endDate);
                    break;
                case [false, false]:
                    $products = $products->whereBetween('created_at', [$startDate, $endDate]);
                    break;
            }

            $products = $products->get();
        } catch (Exception $e) {
            return false;
        }

        return $products;
    }

    public function productDetails($id)
    {
        return Product::find($id);
    }

    /**
     * For single product prices line graph ajax request
     *
     * @param int $days
     *
     * @return JsonResponse
     */
    public function getValuesForLineGraph(int $days = 7): JsonResponse
    {
        $today  = Carbon::now()->endOfDay();
        $before = Carbon::now()->subDays($days)->startOfDay();

        $purchasesTransactions = Transaction::with('purchasePriceDuringTransaction:id,value')
            ->where('type', 'Purchase')
            ->whereBetween('created_at', [$before, $today])
            ->get(['id', 'purchase_price_id']);

        $salesTransactions = Transaction::with('salesPriceDuringTransaction:id,value')
            ->where('type', 'Sale')
            ->whereBetween('created_at', [$before, $today])
            ->get(['id', 'sales_price_id']);


        $purchases = [];
        $sales     = [];
        $maxSize   = max([sizeof($purchasesTransactions), sizeof($salesTransactions)]);

        for ($i = 0; $i < $maxSize; $i++) {
            $purchases[] = isset($purchasesTransactions[$i])
                ? $purchasesTransactions[$i]->purchasePriceDuringTransaction->value
                : 0;
            $sales[]     = isset($salesTransactions[$i])
                ? $salesTransactions[$i]->salesPriceDuringTransaction->value
                : 0;
        }

        return response()->json(
            [
                "purchases" => $purchases,
                "sales"     => $sales
            ]
        );
    }
}
