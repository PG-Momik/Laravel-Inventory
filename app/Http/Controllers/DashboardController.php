<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Cassandra\Exception\DivideByZeroException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

class DashboardController extends Controller
{
    /**
     * Returns dashboard.index as view
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $yesterdaysTransactionQuantity = $this->getYesterdaysTransactionQuantity();

            $monthlyTransactionsArray          = $this->getMonthlyTransactionsAggregateQuantity('avg');
            try {
                $monthlyTransactionQuantityAverage = $this->extractAverage($monthlyTransactionsArray);
            } catch (DivideByZeroException $e) {
                $monthlyTransactionQuantityAverage = 0;
            }

            $overallPurchaseTransactionQuantity = $this->getOverallPurchaseQuantity();

            $overallSalesTransactionQuantity = $this->getOverallSalesQuantity();
        } catch (Exception $e) {
        }

        $cardsValues = [
            'yesterdaysTransactionQuantity'      => $yesterdaysTransactionQuantity ?? 0,
            'monthlyTransactionQuantityAverage'  => $monthlyTransactionQuantityAverage ?? 0,
            'overallPurchaseTransactionQuantity' => $overallPurchaseTransactionQuantity ?? 0,
            'overallSalesTransactionQuantity'    => $overallSalesTransactionQuantity ?? 0
        ];

        return view('dashboard.index')->with(compact('cardsValues'));
    }


    public function test(): view
    {
        return view('dashboard.test');
    }


    /**
     * Used in index() for getting number of transactions made yesterday (int)
     *
     * @return int
     */
    public function getYesterdaysTransactionQuantity(): int
    {
        return Transaction::whereDate('created_at', Carbon::yesterday())->pluck('quantity')->sum() ?? 0;
    }

    /**
     * Used in index() for getting monthly transactions average
     * Can return sum or average of transactions as a whole
     *
     * @param  $aggregate
     * @return Collection
     */
    public function getMonthlyTransactionsAggregateQuantity($aggregate): Collection
    {
        $aggregateQuery = $aggregate == 'sum' ? "sum(quantity) as sum" : "avg(quantity) as avg";

        return DB::table('transactions')
            ->select(DB::raw("MONTH(created_at) as month, $aggregateQuery"))
            ->groupByRaw('MONTH(created_at)')
            ->get();
    }

    /**
     * Used in index() for getting overall purchase
     *
     * @return int
     */
    public function getOverallPurchaseQuantity(): int
    {
        return Transaction::where('type', '=', Transaction::TYPE['purchase'])
            ->pluck('quantity')
            ->sum() ?? 0;
    }

    /**
     * Used in index() for getting overall sales
     *
     * @return int
     */
    public function getOverallSalesQuantity(): int
    {
        return Transaction::where('type', '=', Transaction::TYPE['sales'])
            ->pluck('quantity')
            ->sum();
    }

    /**
     * Returns values for dashboard line-graph based on the type of viewing mode(select option)
     * Overall returns The total transaction quantity grouped by year
     * Annual returns the annual transaction quantity grouped by month
     * Default returns the monthly transaction quantity grouped by days (till 32)
     *
     * @param  $type
     * @return JsonResponse
     */
    public function getValuesForLineGraph($type = ''): JsonResponse
    {
        return match ($type) {
            'overall' => $this->getOverallAnnualTransactions(),
            'annual' => $this->getOneYearsMonthlyTransactions(Carbon::now()->format('Y')),
            default => $this->getOneMonthsDailyTransactions(2022, Carbon::now()->format('m')),
        };
    }


    /**
     * Returns collection of Transaction Model based on product id
     * Set $type = Purchase or Sales if Collection of Purchase or Sales transaction is needed
     * Set $quantity = true if the return type needs to be only quantity of $type
     * Set $aggregate = avg if average quantity per transaction is needed. Default behaviour is sum
     *
     * @param  $productId
     * @param  $type
     * @param  $quantity
     * @param  $aggregate
     * @return float|int|Collection
     */
    public function transactionDetails(
        $productId,
        $type = '',
        $quantity = false,
        $aggregate = 'sum'
    ): float | int | Collection {
        $transactions = Transaction::query();
        $transactions->where('product_id', '=', $productId);
        $transactions->when(
            !empty($type),
            function ($transactions) use ($type) {
                $transactions->where('type', '=', $type);
            }
        );

        if ($quantity) {
            if ($aggregate != 'sum') {
                return $transactions->get()->avg('quantity');
            }

            return $transactions->get()->sum('avg');
        }

        return $transactions->get();
    }

    /**
     * Returns daily purchase or sale transaction quantity of a month as an assoc array,
     * [day => sumOfTransactionQuanityThatDay]
     * Used by getOneMonthsDailyTransactions()
     *
     * @param  $type
     * @param  $year
     * @param  $month
     * @return Collection
     */
    public function getOneMonthsDaily($type, $year, $month): Collection
    {
        return DB::table('transactions')
            ->select(DB::raw('DAY(created_at) as day, SUM(quantity) as sum'))
            ->whereRaw("type= '$type' AND YEAR(created_at) = $year AND MONTH(created_at) = $month")
            ->groupByRaw('MONTH(created_at), DAY(created_at)')
            ->pluck('sum', 'day');
    }

    /**
     * Returns monthly purchase or sale transaction quantity of a year as an assoc array,
     * [month => sumOfTransactionQuanityThatMonth]
     * Used by getOneYearsMonthlyTransactions()
     *
     * @param  $type
     * @param  $year
     * @return Collection
     */
    public function getOneYearsMonthly($type, $year): Collection
    {
        return DB::table('transactions')
            ->select(DB::raw('MONTH(created_at) as month, SUM(quantity) as sum'))
            ->whereRaw("type= '$type' AND YEAR(created_at) = $year")
            ->groupByRaw('MONTH(created_at)')
            ->pluck('sum', 'month');
    }


    /**
     * Returns annual purchase or sale transaction quantity as an assoc array,
     * [year => sumOfTransactionQuantityThatYear]
     * Used by getOverallAnnualTransactions()
     *
     * @param  $type
     * @return Collection
     */
    public function getOverallAnnual($type): Collection
    {
        return DB::table('transactions')
            ->select(DB::raw('YEAR(created_at) as year, SUM(quantity) as sum'))
            ->whereRaw("type= '$type'")
            ->groupByRaw('YEAR(created_at)')
            ->pluck('sum', 'year');
    }

    /**
     * Uses geOneMonthsDaily() for each type of transaction to return a json array of dailyPurchases and dailySales
     * Used by getValuesForLineGraph()
     *
     * @param  $year
     * @param  $month
     * @return JsonResponse
     */
    public function getOneMonthsDailyTransactions($year, $month): JsonResponse
    {
        $purchases      = $this->getOneMonthsDaily('Purchase', $year, $month)->toArray();
        $sales          = $this->getOneMonthsDaily('Sale', $year, $month)->toArray();
        $dailyPurchases = [];
        $dailySales     = [];

        for ($i = 1; $i <= 32; $i++) {
            $dailyPurchases[$i] = array_key_exists($i, $purchases) ? $purchases[$i] : 0;
            $dailySales[$i]     = array_key_exists($i, $sales) ? $sales[$i] : 0;
        }

        return response()->json(
            [
                "dailyPurchases" => $dailyPurchases,
                "dailySales"     => $dailySales
            ]
        );
    }

    /**
     * Uses getOneYearsMonthly() for each month of a year  to return a json array of monthlyPurchases and monthlySales
     * Used by getValuesForLineGraph()
     *
     * @param  $year
     * @return JsonResponse
     */
    public function getOneYearsMonthlyTransactions($year): JsonResponse
    {
        $sales            = $this->getOneYearsMonthly('Sale', $year)->toArray();
        $purchases        = $this->getOneYearsMonthly('Purchase', $year)->toArray();
        $monthlyPurchases = [];
        $monthlySales     = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthlyPurchases[$i] = array_key_exists($i, $purchases) ? $purchases[$i] : 0;
            $monthlySales[$i]     = array_key_exists($i, $sales) ? $sales[$i] : 0;
        }

        return response()->json(
            [
                "monthlyPurchases" => $monthlyPurchases,
                "monthlySales"     => $monthlySales
            ]
        );
    }

    /**
     * Uses getOverallAnnual() for each year since inception to return a json array of annualPurchases and annualSales
     * Used by getValuesForLineGraph()
     *
     * @return JsonResponse
     */
    public function getOverallAnnualTransactions(): JsonResponse
    {
        $sales           = $this->getOverallAnnual('Sale')->toArray();
        $purchases       = $this->getOverallAnnual('Purchase')->toArray();
        $annualPurchases = [];
        $annualSales     = [];

        for ($i = 2020; $i <= 2023; $i++) {
            $annualPurchases[$i] = array_key_exists($i, $purchases) ? $purchases[$i] : 0;
            $annualSales[$i]     = array_key_exists($i, $sales) ? $sales[$i] : 0;
        }

        return response()->json(
            [
                "annualPurchases" => $annualPurchases,
                "annualSales"     => $annualSales
            ]
        );
    }


    /**
     * Returns total quantity of products in stock grouped by either category or product
     *
     * @param  $type
     * @return JsonResponse
     */
    public function getValuesForPieChart($type = ''): JsonResponse
    {
        if ($type == 'Products') {
            return response()->json(Product::pluck('quantity', 'name'));
        }

        return response()->json(Category::withSum('products', 'quantity')->pluck('products_sum_quantity', 'name'));
    }



    //-------------Utility functions---------------------------

    /**
     * Extracts average from collection
     * Used by $monthlyTransactionQuantityAverage
     *
     * @param  $array
     * @return float|int
     */
    public function extractAverage($array): float | int
    {
        $sum = 0;
        foreach ($array as $item) {
            $sum = $item->avg + $sum;
        }

        return $sum / $array->count();
    }

    /**
     * For array of objects
     * Removes created_at field and updated_at field
     * Replaces null with zero if products_sum_quantity = 0
     *
     * @param  $array
     * @return mixed
     */
    public function sanitizeObjectArray($array): mixed
    {
        foreach ($array as $key => $item) {
            $item        = $this->sanitizeObject($item);
            $array[$key] = $item;
        }

        return $array;
    }

    /**
     * For single object
     * Removes created_at field and updated_at field
     * Replaces null with zero if products_sum_quantity = 0
     *
     * @param  $object
     * @return mixed
     */
    public function sanitizeObject($object): mixed
    {
        if ($object->created_at) {
            unset($object->created_at);
        }
        if ($object->updated_at) {
            unset($object->updated_at);
        }
        if (is_null($object->products_sum_quantity)) {
            $object->products_sum_quantity = 0;
        }

        return $object;
    }
}
