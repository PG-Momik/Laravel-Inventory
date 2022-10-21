<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $searchKeyword = $request['search-field'] ?? '';
        $products = Product::with('category')->paginate(10);

        if ( !empty($searchKeyword) ) {
            $products = Product::with('category')
                ->where('products.name', 'LIKE', "%$searchKeyword%")
                ->paginate(10);
        }

        return view('products.index')->with(compact('products', 'searchKeyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('products.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate(apply_validation_to(['']));
        } catch ( Exception $e ) {
        }

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return View
     */
    public function show($id): View
    {
        $product = Product::with('user')->with('category')->find($id);

        return view('products.product')->with(compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
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
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        //Request Validation goes

        $changes = 0;
        //To check if there's any change in quantity

        $product->name        = $request->name;
        $product->category_id = $request->category_id;
        $product->price       = $request->price;
        $product->discount    = $request->discount;
        $product->description = $request->description;

        $changes           = $request->quantity - $product->quantity;
        $product->quantity = $request->quantity;

        if ( $changes != 0 ) {
            $transaction             = new Transaction();
            $transaction->user_id    = Auth::user()->id;
            $transaction->product_id = $product->id;
            $transaction->type       = $changes > 0 ? $transaction::ADDED : $transaction::REMOVED;
            $transaction->quantity   = abs($changes);

            try {
                $transaction->save();
            } catch ( Exception $e ) {
                //DO nothing for now
                dd($e);
            }
        }

        try {
            $file = $request->file('productImage') ?? '';

            if ( $file ) {
                $newFilename = $request->productImage->getClientOriginalName();
                $file->storeAs('public/images', $newFilename);
                $product->image = $newFilename;
            }

            $product->update();
            session()->flash('success', "Product info updated.");
        } catch ( Exception $e ) {
            session()->flash('warning', "Something went wrong.");

            return redirect()->route('products.show', ['product' => $product]);
        }

        return redirect()->route('products.show', ['product' => $product]);
    }


    public function showTrash(Request $request): View
    {
        $searchKeyword = $request['search-field'] ?? '';

        if ( empty($searchKeyword) ) {
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
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            $product->delete();
            session()->flash('warning', 'Product moved to trash.');

        } catch ( Exception $e ) {
            session()->flash('warning', 'Something went wrong. Try Again.');
        }

        return redirect()->back();
    }

    /**
     * Restore trashed product
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
        } catch ( Exception $e ) {
            session()->flash('warning', 'Something went wrong.');
        }

        return redirect()->back();
    }


    /**
     * Remove product from db
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
        } catch ( Exception $e ) {
            session()->flash('warning', "Something went wrong. Try again.");
        }

        return redirect()->back();
    }


}
