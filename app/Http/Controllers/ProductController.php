<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\PurchasePrice;
use App\Models\SalesPrice;
use App\Models\Transaction;
use App\Models\TransactionType;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param SearchRequest $request
     *
     * @return View
     */
    public function index(SearchRequest $request): View
    {
        $searchKeyword = $request->validated('search-field') ?? '';

        $products   = Product::with('category')
            ->when(
                !empty($searchKeyword),
                function ($products) use ($searchKeyword) {
                    return $products->where('name', 'like', "%$searchKeyword%")
                        ->orWhere('description', 'like', "%$searchKeyword%");
                }
            )
            ->paginate(10);
        $categories = Category::pluck('name', 'id');

        return view('noob.products.index')->with(compact('products', 'searchKeyword', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('noob.products.add')->with(['categories' => Category::get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CreateProductRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $product                = new Product();
            $product->registered_by = Auth::id();
            $product->name          = $request->validated('name');
            $product->category_id   = $request->validated('category');
            $product->quantity      = $request->validated('quantity');
            $product->discount      = $request->validated('discount');
            $product->description   = $request->validated('description');


            $file = $request->file('productImage') ?? '';

            if ($file) {
                $newFilename = $request->productImage->getClientOriginalName();
                $file->storeAs('public/images', $newFilename);
                $product->image = $newFilename;
            }

            $product->save();

            $purchasePrice = $this->priceFactory(
                priceType: 'purchase',
                product  : $product,
                price    : $request->validated('purchasePrice')
            );

            $salesPrice = $this->priceFactory(
                priceType: 'sales',
                product  : $product,
                price    : $request->validated('salesPrice')
            );

            $transaction                    = new Transaction();
            $transaction->user_id           = Auth::id();
            $transaction->product_id        = $product->id;
            $transaction->sales_price_id    = $salesPrice->id;
            $transaction->purchase_price_id = $purchasePrice->id;
            $transaction->type              = TransactionType::PURCHASE;
            $transaction->quantity          = $request->validated('quantity');
            $transaction->discount          = $request->validated('discount');

            $transaction->save();
            DB::commit();
            session()->flash('success', 'Product added.');
        } catch (Exception) {
            DB::rollBack();
            session()->flash('warning', 'Something went wrong. Try again.');
        }


        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     *
     * @return View
     */
    public function show(Product $product): View
    {
        $product->load('registrant.roles', 'category');

        return view('noob.products.product')->with(compact('product'));
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

        return view('noob.products.edit')->with(compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     *
     * @return RedirectResponse
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        DB::beginTransaction();
        if ($request['transactionType']) {
            if ($request['transactionType'] === TransactionType::PURCHASE) {
                $class                 = 'App\Models\PurchasePrice';
                $changedColumn         = 'purchase_price_id';
                $unchangedColumn       = 'sales_price_id';
                $latestUnchangedColumn = 'latestSalesPrice';
            } else {
                $class                 = 'App\Models\SalesPrice';
                $changedColumn         = 'sales_price_id';
                $unchangedColumn       = 'purchase_price_id';
                $latestUnchangedColumn = 'latestPurchasePrice';
            }

            $changes = abs($request->validated('quantity') - $product->quantity);

            $product->load($latestUnchangedColumn);

            $newChangedColumnValue             = new $class();
            $newChangedColumnValue->product_id = $product->id;
            $newChangedColumnValue->value      = $request->validated('price');
            $newChangedColumnValue->save();

            $transaction                   = new Transaction();
            $transaction->user_id          = Auth::id();
            $transaction->product_id       = $product->id;
            $transaction->$changedColumn   = $newChangedColumnValue->id;
            $transaction->$unchangedColumn = $product->$latestUnchangedColumn->id;
            $transaction->type             = $request['transactionType'];
            $transaction->quantity         = $changes;
            $transaction->discount         = $request->validated('discount') ?? 0;

            $transaction->save();
        }

        $product->category_id = $request->validated('category_id');
        $product->quantity    = $request->validated('quantity');
        $product->discount    = $request->validated('discount') ?? $product->discount;
        $product->name        = $request->validated('name');
        $product->description = $request->validated('description');


        try {
            $file = $request->file('productImage') ?? '';

            if ($file) {
                $newFilename = $request->productImage->getClientOriginalName();
                $file->storeAs('public/images', $newFilename);
                $product->image = $newFilename;
            }

            $product->update();
            DB::commit();
            session()->flash('success', "Product info updated.");
        } catch (Exception) {
            DB::rollBack();
            session()->flash('warning', "Something went wrong.");
        }

        return back();
    }


    /**
     * Returns products.trashed view with products where deleted_at exists
     *
     * @param SearchRequest $request
     *
     * @return View
     */
    public function showTrash(SearchRequest $request): View
    {
        $searchKeyword = $request->validated('search-field') ?? '';

        $products = Product::with('category')
            ->onlyTrashed()
            ->when(
                !empty($searchKeyword),
                function ($products) use ($searchKeyword) {
                    $products->where('products.name', 'LIKE', "%$searchKeyword%");
                }
            )
            ->paginate(10);

        return view('noob.products.trashed')->with(compact('products', 'searchKeyword'));
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

        return redirect()->route('products.index');
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
     * @param SearchRequest $request
     *
     * @return mixed
     */
    public function filterProducts(SearchRequest $request): mixed
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
        $dateFormat = 'Y-m-d';
        $startDate  = $filterParams['startDate'] ?? 0;
        $endDate    = $filterParams['endDate'] ?? 0;

        try {
            $products = Product::with('category');

            $products = $filterParams['quantity'] > 0
                ? $products->where('quantity', '>=', $filterParams['quantity'])
                : $products;

            if (!empty($filterParams['category_ids'])) {
                $products = $products->whereIn('category_id', $filterParams['category_ids']);
            }
            /**
             * if date is zero do not use it for query
             * therefore when one date is zero and one is not zero,
             * find record where date is equal to or greater/smaller than non-zero date
             *
             * if both are not zero, find record in between the range.
             * */
            switch ([isZero($startDate), isZero($endDate)]) {
                case [false, true]:
                    $startDate = Carbon::createFromFormat($dateFormat, $filterParams['startDate'])->startOfDay();
                    $products  = $products->whereDate('created_at', '>=', $startDate);
                    break;
                case [true, false]:
                    $endDate  = Carbon::createFromFormat($dateFormat, $filterParams['endDate'])->endOfDay();
                    $products = $products->whereDate('created_at', '<=', $endDate);
                    break;
                case [false, false]:
                    $startDate = Carbon::createFromFormat($dateFormat, $filterParams['startDate'])->startOfDay();
                    $endDate   = Carbon::createFromFormat($dateFormat, $filterParams['endDate'])->endOfDay();
                    $products  = $products->whereBetween('created_at', [$startDate, $endDate]);
                    break;
            }
            $products = $products->get();
        } catch (Exception) {
            return false;
        }

        return $products;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function productDetails($id): mixed
    {
        return Product::findOrFail($id);
    }

    /**
     * For single product prices line graph ajax request
     *
     * @param string $type
     * @param int $days
     *
     * @return JsonResponse
     */
    public function getValuesForLineGraph(string $type, int $days = 7): JsonResponse
    {
        $today  = Carbon::now()->endOfDay();
        $before = Carbon::now()->subDays($days)->startOfDay();

        $neededRelationship = $type . 'PriceDuringTransaction';

        $foreignKey   = $type . '_price_id';
        $transactions = Transaction::with($neededRelationship . ":id,value")
            ->where('type', TransactionType::ALL[$type])
            ->whereBetween('created_at', [$before, $today])
            ->get(['id', $foreignKey, 'created_at']);

        $returnArray = [];

        foreach ($transactions as $transaction) {
            $arrIndex                 = $transaction->created_at->format('y-M-d');
            $returnArray[][$arrIndex] = $transaction->$neededRelationship->value;
        }

        return response()->json($returnArray);
    }


    /**
     * Returns an instance of either sales price or purchase price depending on param.
     *
     * @param string $priceType
     * @param Product $product
     * @param $price
     *
     * @return SalesPrice|PurchasePrice
     */
    public function priceFactory(string $priceType, Product $product, $price): SalesPrice | PurchasePrice
    {
        if ($priceType == 'sales') {
            $object             = new SalesPrice();
            $object->product_id = $product->id;
            $object->value      = $price;
            $object->save();

            return $object;
        }

        $object             = new PurchasePrice();
        $object->product_id = $product->id;
        $object->value      = $price;
        $object->save();

        return $object;
    }

}
