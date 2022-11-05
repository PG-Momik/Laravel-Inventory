<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <title>Transaction PDF</title>
</head>
<body class="container">

<h1 class="text-center">Brand Eta</h1>
<h3 class="text-center">Mahalaxmisthan, Lalitpur</h3>
<br>
<div class="">
    <span><strong>Date:</strong></span>
    <span class="" style=" border-bottom: 1px dotted #000; text-decoration: none;">{{$transaction->created_at->format('l jS \of F')}}</span>
</div>

<br>
<table class="table" id="table">
    <tr class="">
        <th scope="col">#</th>
        <th scope="col">Product ID</th>
        <th scope="col">Particular</th>
        <th scope="col">Quantity</th>
        <th scope="col">Price</th>
        <th scope="col">Discount</th>
        <th scope="col">Total</th>
    </tr>
    <tbody>

    @php
        $total    = 0;
        $price    = 0;
        $discount = 0;
        $quantity = $transaction->quantity;

        if($transaction->type == $transaction::TYPE['purchase']){
            $price = $transaction->purchasePriceDuringTransaction->value;
        }else{
            $price = $transaction->salesPriceDuringTransaction->value;
            $discount = $transaction->discount;
        }
        $beforeDiscount = $quantity*$price;
        $total = $beforeDiscount - (($beforeDiscount/100) * $discount);
    @endphp

    <tr>
        <td>1</td>
        <td>{{$transaction->product_id}}</td>
        <td>{{$transaction->product->name}}</td>
        <td>{{$quantity}}</td>
        <td>{{$price}}</td>
        <td>{{$discount??0}}</td>
        <td>{{$total}}</td>
    </tr>
    </tbody>
</table>
<br>
<div class="text-end">
    <span><strong>Transaction By:</strong></span>
    <span class="" style=" border-bottom: 1px dotted #000; text-decoration: none;">{{$transaction->user->name}}</span>
</div>

</body>
<style>
    .border-this {
        border: 1px solid black;
    }

    .flex-this {
        display: flex;
    }

    table {
        border: 1px solid black;
    }

    #table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #table td, #table th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #table tr:hover {
        background-color: #ddd;
    }

    #table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #2c2c2c;
        color: white;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>

</html>
