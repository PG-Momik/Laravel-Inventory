<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Role;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AjaxController extends Controller
{

    /**
     * Returns Categories as json for Products.edit.modal
     * @param int $categoryId
     * @return JsonResponse
     */
    public function categoryProducts(int $categoryId): JsonResponse
    {
        $products = Product::where('category_id', '=', $categoryId)->get(['id', 'name']);

        return response()->json(array('msg' => $products, 200));
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function filterProducts(Request $request): mixed
    {
        return $this->filteredProducts($request['filterParams']);
    }

    /**
     * Returns Products after applying filter params
     * @param $filterParams
     * @return mixed
     */
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


    /**
     * Returns  json value needed for categories.category doughnut graph
     * @param int $id
     * @param bool $detailed
     * @return bool|string
     */
    function getCategoryBasedStats(int $id, bool $detailed = false): bool | string
    {
        $returnJson = array();

        $total                = Product::sum('quantity');
        $sumOfRelatedProducts = Product::where('category_id', '=', $id)->sum('quantity');
        $sumOfOtherProducts   = $total - $sumOfRelatedProducts;

        $returnJson['sumOfRelatedProducts'] = $sumOfRelatedProducts;
        $returnJson['sumOfOtherProducts']   = $sumOfOtherProducts;

        if ( $detailed ) {
            $individualQuantities               = Product::where('category_id', '=', $id)->pluck('quantity', 'name');
            $returnJson['individualQuantities'] = $individualQuantities;
            unset($returnJson['sumOfRelatedProducts']);
        }


        return json_encode($returnJson);
    }

    /**
     * Returns json value needed for roles.role bar graph
     * @return bool| String
     */
    public function getRoleBasedStats(): bool | string
    {

        $numberOfAdmins     = Role::withCount('users')->find(1)->users_count;
        $numberOfNonAdmin   = Role::withCount('users')->find(2)->users_count;
        $totalNumberOfUsers = $numberOfAdmins + $numberOfNonAdmin;

        return json_encode(
            [
                'totalNumberOfUsers' => $totalNumberOfUsers,
                'numberOfAdmins'     => $numberOfAdmins,
                'numberOfNonAdmin'   => $numberOfNonAdmin
            ]
        );

    }

}

