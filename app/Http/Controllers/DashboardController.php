<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use JetBrains\PhpStorm\NoReturn;

class DashboardController extends Controller
{


    public function index(): View
    {
        return view('dashboard.index');
    }


    public function users(Request $request) {}

    public function products()
    {
        return view('dashboard.products');
    }

    public function transactions()
    {
        return view('dashboard.transactions');
    }

    public function test()
    {
        return view('dashboard.test');
    }
    #[NoReturn] public function testValue(Request $request)
    {
        dd($request->to);
    }

}
