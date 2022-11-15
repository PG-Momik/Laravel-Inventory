<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
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
            $yesterdaysQty = $this->yesterdaysQtyFor(Transaction::TYPE['purchase'])
                +
                $this->yesterdaysQtyFor(Transaction::TYPE['sales']);

            $monthlyTransactionQtyAvg = $this->extractAverage(
                $this->monthlyAverageQtyFor(Transaction::TYPE['purchase']),
                $this->monthlyAverageQtyFor(Transaction::TYPE['sales'])
            );

            $overallPurchaseTransactionQty = $this->overallQtyFor(Transaction::TYPE['purchase']);
            $overallSalesTransactionQty    = $this->overallQtyFor(Transaction::TYPE['sales']);
        } catch (Exception $e) {
        }

        $cardsValues       = [
            'yesterdaysTransactionQuantity'     => $yesterdaysQty ?? 0,
            'monthlyTransactionQuantityAverage' => $monthlyTransactionQtyAvg ?? 0,
            'totalPurchaseTransactionQuantity'  => $overallPurchaseTransactionQty ?? 0,
            'totalSalesTransactionQuantity'     => $overallSalesTransactionQty ?? 0
        ];
        $cardInitialRoutes = [
            route('yesterdays-transactions'),
            route('monthly-transactions', ['month' => Carbon::now()->format('M')]),
            route('yearly-transactions', ['year' => Carbon::now()->format('Y'), 'type' => 'purchase']),
            route('yearly-transactions', ['year' => Carbon::now()->format('Y'), 'type' => 'sale'])
        ];

        return view('dashboard.index')->with(compact('cardsValues', 'cardInitialRoutes'));
    }


    public function test(): view
    {
        return view('dashboard.test');
    }

    /**
     * Returns collection of Transaction Model based on product id
     * Set $type = Purchase or Sales if Collection of Purchase or Sales transaction is needed
     * Set $quantity = true if the return type needs to be only quantity of $type
     * Set $aggregate = avg if average quantity per transaction is needed. Default behaviour is sum
     *
     * @param  $productId
     * @param string $type
     * @param bool $quantity
     * @param string $aggregate
     *
     * @return float|int|Collection
     */
    public function transactionDetails(
        $productId,
        string $type = '',
        bool $quantity = false,
        string $aggregate = 'sum'
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
     *
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
     *
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
     *
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
     * Used in index() for getting overall purchase/sale
     *
     * @param string $type
     *
     * @return int
     */
    public function overallQtyFor(string $type): int
    {
        return Transaction::where('type', '=', $type)
            ->sum('quantity') ?? 0;
    }

    /**
     * Returns yesterday's purchase/sale quantity based on param
     *
     * @param string $type
     *
     * @return int
     */
    public function yesterdaysQtyFor(string $type): int
    {
        return Transaction::whereDate('created_at', Carbon::yesterday())
            ->where('type', $type)
            ->sum('quantity') ?? 0;
    }

    /**
     * Returns monthly average quantity of purchase/sale based on param
     *
     * @param string $type
     *
     * @return float|int
     */
    public function monthlyAverageQtyFor(string $type): float | int
    {
        return Transaction::whereYear('created_at', Carbon::now()->year)
                ->where('type', $type)
                ->sum('quantity') / 12;
    }

    /**
     * --------------------Ajax request methods--------------------
     */

    /**
     * Uses geOneMonthsDaily() for each type of transaction to return a json array of dailyPurchases and dailySales
     * Used by getValuesForLineGraph()
     *
     * @param  $year
     * @param  $month
     *
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
     *
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
     * @param string $only
     *
     * @return JsonResponse
     */
    public function getOverallAnnualTransactions(string $only = ''): JsonResponse
    {
        $sales           = $this->getOverallAnnual(Transaction::TYPE['sales'])->toArray();
        $purchases       = $this->getOverallAnnual(Transaction::TYPE['purchase'])->toArray();
        $annualPurchases = [];
        $annualSales     = [];

        for ($i = 2021; $i <= 2022; $i++) {
            $annualPurchases[$i] = array_key_exists($i, $purchases) ? $purchases[$i] : 0;
            $annualSales[$i]     = array_key_exists($i, $sales) ? $sales[$i] : 0;
        }

        if (empty($only)) {
            return response()->json(
                [
                    "annualPurchases" => $annualPurchases,
                    "annualSales"     => $annualSales
                ]
            );
        }

        return $only == 'Purchase'
            ? response()->json(['annualPurchases' => $annualPurchases])
            : response()->json(['annualSales' => $annualSales]);
    }

    /**
     * Returns values for dashboard line-graph based on the type of viewing mode(select option)
     * Overall returns The total transaction quantity grouped by year
     * Annual returns the annual transaction quantity grouped by month
     * Default returns the monthly transaction quantity grouped by days (till 32)
     *
     * @param string $type
     *
     * @return JsonResponse
     */
    public function getValuesForLineGraph(string $type = ''): JsonResponse
    {
        return match ($type) {
            'overall' => $this->getOverallAnnualTransactions(),
            'annual' => $this->getOneYearsMonthlyTransactions(Carbon::now()->format('Y')),
            default => $this->getOneMonthsDailyTransactions(2022, Carbon::now()->format('m')),
        };
    }

    /**
     * Returns total quantity of products in stock grouped by category/product based on param
     *
     * @param string $type
     *
     * @return JsonResponse
     */
    public function getValuesForPieChart(string $type = ''): JsonResponse
    {
        if ($type == 'Products') {
            return response()->json(Product::pluck('quantity', 'name'));
        }

        return response()->json(Category::withSum('products', 'quantity')->pluck('products_sum_quantity', 'name'));
    }

    /**
     * Returns extra values for card 0 to 3 on dashboard.index page
     *
     * @param int $cardNo
     *
     * @return JsonResponse
     */
    public function getValueForCard(int $cardNo): JsonResponse
    {
        return match ($cardNo) {
            0 => response()->json(
                [
                    'yesterdaysPurchaseQuantity' => $this->yesterdaysQtyFor(Transaction::TYPE['purchase']),
                    'yesterdaysSalesQuantity'    => $this->yesterdaysQtyFor(Transaction::TYPE['sales']),
                ]
            ),
            1 => response()->json(
                [
                    'monthlyPurchaseQuantityAverage' => $this->monthlyAverageQtyFor(Transaction::TYPE['purchase']),
                    'monthlySalesQuantityAverage'    => $this->monthlyAverageQtyFor(Transaction::TYPE['sales']),
                ]
            ),
            2 => $this->getOverallAnnualTransactions(Transaction::TYPE['purchase']),
            3 => $this->getOverallAnnualTransactions(Transaction::TYPE['sales']),
            default => response()->json(['message' => 'Not found.'], 404),
        };
    }

    /**
     * --------------------End of Ajax request methods--------------------
     */

    /**
     * --------------------Utility functions--------------------
     */

    /**
     * Extracts average from array
     * Used by $monthlyTransactionQuantityAverage
     *
     * @param  $array
     *
     * @return float|int
     */
    public function extractAverage(...$array): float | int
    {
        $sum = 0;
        foreach ($array as $item) {
            $sum = $item + $sum;
        }

        try {
            return $sum / sizeof($array);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * --------------------End of Utility functions--------------------
     */
}
