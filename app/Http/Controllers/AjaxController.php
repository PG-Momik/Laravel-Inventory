<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AjaxController extends Controller
{

    /**
     * @param int $categoryId
     * @return JsonResponse
     */
    public function  categoryProducts(int $categoryId):JsonResponse
    {
        $products = Product::where('category_id', '=', $categoryId)->get(['id', 'name']);
        return response()->json(array('msg'=>$products,200));
    }
}
