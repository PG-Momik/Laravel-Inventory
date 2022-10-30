<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AjaxController extends Controller
{

    /**
     * @param int $categoryId
     * @return JsonResponse
     */
    public function categoryProducts(int $categoryId): JsonResponse
    {
        $products = Product::where('category_id', '=', $categoryId)->get(['id', 'name']);

        return response()->json(array('msg' => $products, 200));
    }


    public function filterProducts(Request $request)
    {
//            return Product::with('category')
//                ->whereBetween('created_at', [$startDate, $endDate])
//                ->orWhere('quantity', '=', $filterParams['quantity'])
//                ->whereIn('category_id', $filterParams['category_ids'])
//                ->get();
        return $this->filteredProducts($request['filterParams']);
    }

    function filteredProducts($filterParams): mixed
    {
        $startFormat     = 'Y-m-d';
        $startDateString = implode(
            '-',
            [$filterParams['startYear'], $filterParams['startMonth'], $filterParams['startDay']]
        );

        if ( isZero($filterParams['startYear']) ) {
            $startDateString = implode('-', [$filterParams['startMonth'], $filterParams['startDay']]);
            $startFormat     = 'm-d';
        }
        $startDate     = Carbon::createFromFormat($startFormat, $startDateString)->startOfDay();
        $endFormat     = 'Y-m-d';
        $endDateString = implode('-', [$filterParams['endYear'], $filterParams['endMonth'], $filterParams['endDay']]);

        if ( isZero($filterParams['endYear']) ) {
            $endDateString = implode('-', [$filterParams['endMonth'], $filterParams['endDay']]);
            $endFormat     = 'm-d';
        }
        $endDate = Carbon::createFromFormat($endFormat, $endDateString)->endOfDay();


        try {

            $products = Product::with('category');

            $products = $filterParams['quantity'] > 0
                ? $products->where('quantity', '=', $filterParams['quantity'])
                : $products;
            if ( !empty($filterParams['category_ids']) ) {
                $products = $products->whereIn('category_id', $filterParams['category_ids']);
            }
            switch ( [$startDateString == '0-0', $endDateString == '0-0'] ) {
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

        } catch ( Exception $e ) {
            return false;
        }

        return $products;
    }


}

