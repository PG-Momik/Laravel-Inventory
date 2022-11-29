<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Category::class);
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
     * @param CreateProductRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CreateProductRequest $request): RedirectResponse
    {
        try {
            $category       = new Category();
            $category->name = $request['name'];
            $category->save();
            session()->flash('success', 'Category added.');
        } catch (Exception) {
            session()->flash('warn', 'Something went wrong.');
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     *
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
     * @param Category $category
     *
     * @return Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     *
     * @return Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified category from db.
     *
     * @param Category $category
     *
     * @return RedirectResponse
     */
    public function destroy(Category $category): RedirectResponse
    {
        try {
            $category->delete();
            session()->flash('warning', 'Category moved to trash');
        } catch (Exception) {
            session()->flash('warning', 'Something went wrong. Try Again.');
        }

        return redirect()->route('categories.index');
    }

    /**
     * Shows Trashed Data
     *
     * @param SearchRequest $request
     *
     * @return View
     */
    public function showTrash(SearchRequest $request): View
    {
        $searchKeyword = $request['search-field'] ?? '';
        $categories    = Category::withCount('products')
            ->onlyTrashed()
            ->when(
                !empty($searchKeyword),
                function ($categories) use ($searchKeyword) {
                    return $categories
                        ->where('categories.name', 'LIKE', "%$searchKeyword%")
                        ->orWhere('categories.email', 'LIKE', "%$searchKeyword%");
                }
            )->paginate(10);

        return view('categories.trashed')->with(compact('categories', 'searchKeyword'));
    }

    /**
     * Restore trashed category
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function restore(int $id): RedirectResponse
    {
        try {
            $category = Category::withTrashed()->find($id);
            $category->restore();
            session()->flash('success', "User restored");
        } catch (Exception) {
            session()->flash('warning', 'Something went wrong.');
        }

        return redirect()->back();
    }


    /**
     * Remove category from db
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function hardDelete(int $id): RedirectResponse
    {
        try {
            $category = Category::withTrashed()->find($id);
            $category->forceDelete();
            session()->flash('success', 'Category record destroyed.');
        } catch (Exception) {
            session()->flash('warning', "Something went wrong. Try again.");
        }

        return redirect()->back();
    }


    /**
     * Returns  json value needed for categories.category doughnut graph
     *
     * @param int $id
     * @param bool $detailed
     *
     * @return bool|string
     */
    public function getCategoryBasedStats(int $id, bool $detailed = false): bool | string
    {
        $returnJson = array();

        $total                = Product::sum('quantity');
        $sumOfRelatedProducts = Product::where('category_id', '=', $id)->sum('quantity');
        $sumOfOtherProducts   = $total - $sumOfRelatedProducts;

        $returnJson['sumOfRelatedProducts'] = $sumOfRelatedProducts;
        $returnJson['sumOfOtherProducts']   = $sumOfOtherProducts;

        if ($detailed) {
            $individualQuantities               = Product::where('category_id', '=', $id)->pluck(
                'quantity',
                'name'
            );
            $returnJson['individualQuantities'] = $individualQuantities;
            unset($returnJson['sumOfRelatedProducts']);
        }

        return json_encode($returnJson);
    }
}
