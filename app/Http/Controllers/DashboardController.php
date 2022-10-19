<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $searchKeyword = $request['search-field'] ?? '';

        if ( empty($searchKeyword) ) {
            $users = User::select('users.id', 'users.name', 'users.role_id')
                ->withCount('transactions')
                ->with('roles:id,name')
                ->paginate(10);
        } else {
            $users = User::select('users.id', 'users.name', 'users.role_id')
                ->where('users.name', 'LIKE', "%$searchKeyword%")
                ->orWhere('users.email', 'LIKE', "%$searchKeyword%")
                ->with('roles:id,name')
                ->withCount('transactions')
                ->paginate(10);
        }

        $data = compact('users', 'searchKeyword');

        return view('dashboard.test')->with($data);
    }


}
