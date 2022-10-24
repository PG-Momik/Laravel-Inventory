<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Cost;
use App\Models\Price;
use App\Models\Product;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $categories = Category::all();

        $recentSales = Transaction::where('price_id', '!=', null)
            ->with('productInfo')
            ->with('currentSalesPrice')
            ->latest()->take(5)
            ->get();
        $recentPurchases = Transaction::where('cost_id', '!=', null)
            ->with('productInfo')
            ->with('currentPurchasePrice')
            ->latest()->take(5)
            ->get();

        return view('transactions.index')->with(compact('categories', 'recentSales', 'recentPurchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create(): void {}

    /**
     * Checks transaction type, inserts new entry depending on the transaction type
     * Updates product table quantity, either + or -
     * Creates record for transaction
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $transactionType = $request['transactionType'];
        //validation goes here

        try {
            $product = Product::find($request['productId']);

            $transaction = new Transaction();

            if ( $transactionType == 1 ) {

                $price             = new Price();
                $price->value      = $request['salesPrice'];
                $price->product_id = $request['productId'];
                $price->save();

                $transaction->price_id = $price->id;
                $transaction->quantity = $request['salesQuantity'];

                $product->quantity  = $product->quantity - $request['salesQuantity'];


            } else {

                $cost             = new Cost();
                $cost->value      = $request['purchasePrice'];
                $cost->product_id = $request['productId'];
                $cost->save();

                $transaction->cost_id  = $cost->id;
                $transaction->quantity = $request['purchaseQuantity'];

                $product->quantity = $product->quantity + $request['purchaseQuantity'];


            }

            $transaction->type       = $transactionType == 1 ? Transaction::SALE : Transaction::PURCHASE;
            $transaction->user_id    = Auth::user()->id;
            $transaction->product_id = $request['productId'];
            $transaction->discount   = $request['discount'] ?? 0;

            $transaction->save();
            $product->update();

            session()->flash('success', 'Transaction entry made.');
        } catch ( Exception $e ) {
            session()->flash('error', 'Something went wrong. Transaction entry not made.');
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param Transaction $transaction
     * @return Response
     */
    public function show(Transaction $transaction): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id): Response
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id): Response
    {
        //
    }

}
