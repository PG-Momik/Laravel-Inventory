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
     * @param CreateProductRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CreateProductRequest $request): RedirectResponse
    {
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
            session()->flash('success', 'Product added.');
        } catch (Exception) {
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
     * @param UpdateProductRequest $request
     * @param Product $product
     *
     * @return RedirectResponse
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
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
            session()->flash('success', "Product info updated.");
        } catch (Exception $e) {
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
    public function filteredProducts(
        array $filterParams
    ): mixed {
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

    public function productDetails(
        $id
    ) {
        return Product::find($id);
    }

    /**
     * For single product prices line graph ajax request
     *
     * @param string $type
     * @param int $days
     *
     * @return JsonResponse
     */
    public function getValuesForLineGraph(
        string $type,
        int $days = 7
    ): JsonResponse {
        $today  = Carbon::now()->endOfDay();
        $before = Carbon::now()->subDays($days)->startOfDay();

        $neededRelationship = $type . 'PriceDuringTransaction';

        $foreignKey   = $type . '_price_id';
        $transactions = Transaction::with($neededRelationship . ":id,value")
            ->where('type', Transaction::TYPE[$type])
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

    public function transactionFactory() {}
}
