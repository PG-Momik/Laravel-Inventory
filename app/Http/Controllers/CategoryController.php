<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $searchKeyword = $request['search-field'] ?? '';

        $categories = Category::when(
            !empty($searchKeyword),
            function ($categories) use ($searchKeyword) {
                return $categories->where('name', 'like', "%$searchKeyword%");
            }
        )
            ->withCount('products')
            ->paginate(10);

        return view('categories.index')->with(compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('categories.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $category       = new Category();
            $category->name = $request['name'];
            $category->save();
            session()->flash('success', 'Category added.');
        } catch (Exception $e) {
            session()->flash('warn', 'Something went wrong.');
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  Category $category
     * @return View
     */
    public function show(Category $category): View
    {
        $category->load('products.latestPurchasePrice', 'products.latestSalesPrice');

        return view('categories.category')->with(['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Category $category
     * @return Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Category $category
     * @return Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category $category
     * @return Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
