<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $searchKeyword = $request['search-field'] ?? '';
        if ( empty($searchKeyword) ) {
            $users = Customer::select('users.id', 'users.name', 'users.role_id', 'users.email')
                ->withCount('transactions')
                ->with('roles:id,name')
                ->paginate(10);
        } else {
            $users = Customer::select('users.id', 'users.name', 'users.role_id')
                ->where('users.name', 'LIKE', "%$searchKeyword%")
                ->orWhere('users.email', 'LIKE', "%$searchKeyword%")
                ->with('roles:id,name')
                ->withCount('transactions')
                ->paginate(10);
        }

        return view('users.index')->with(compact('users', 'searchKeyword'));
    }

    /**
     * Display a form to create resource.
     *
     * @return View
     */
    public function create()
    {
        return view('users.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return
     */
    public function store(Request $request): RedirectResponse
    {
        //
        $request->validate(registration_form_validation(['name', 'email', 'role_id', 'password']));
        $user           = new Customer();
        $user->name     = $request['name'];
        $user->email    = $request['email'];
        $user->role_id  = $request['role_id'];
        $user->password = $request['password'];
        try {
            $user->save();
            session()->flash('success', 'User added successfully.');
        } catch ( Exception $e ) {
            session()->flash('error', 'Something went wrong.');
        }

        return redirect('/users/add');
    }


    /**
     * @param Request $request
     * @return Application|Factory|\Illuminate\Contracts\View\View|RedirectResponse|Redirector
     */
    public function show(Request $request): View | RedirectResponse
    {
        //

        $user = Customer::with('roles:id,name')
            ->with('products')
            ->withCount('transactions')
            ->find($request->id);
        if ( is_null($user) ) {
            return redirect('/users');
        }

        return view('users.user')->with(compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @return Response
     */
    public function edit(Request $request)
    {
        $user = Customer::with('roles:id,name')
            ->with('products')
            ->withCount('transactions')
            ->find($request->id);
        if ( is_null($user) ) {
            $request->session()->flash('warning', 'User not found');

            return redirect()->route('users.index');
        }

        return view('users.edit')->with(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Customer $customer
     * @return Response
     */
    public function update(Request $request)
    {
        $request->validate(registration_form_validation(['id', 'name', 'email', 'role_id'], 'update'));
        $user                    = Customer::find($request->id);
        $user->id                = $request->id;
        $user->name              = $request->name;
        $user->email             = $request->email;
        $user->role_id           = $request->role_id;
        $user->email_verified_at = now()->format('Y-m-d H:i:s');
        if ( $request->verifyEmail == "false" ) {
            $user->email_verified_at = null;
        }
        try {
            $user->update();
        } catch ( Exception $e ) {
            session()->flash('warning', "Something went wrong.");

            return redirect()->route('users.show', ['id' => $user->id]);
        }
        session()->flash('success', "User info updated.");

        return redirect()->route('users.show', ['id' => $user->id]);
    }

    /**
     * Remove the specified resource from storage.
     *

     */
    public function delete(Request $request)
    {
        $user = Customer::find($request->id);
        if ( !is_null($user) ) {
            $user->delete();
            session()->flash('warning', 'User moved to trash');
        }

        return redirect('/users');
    }


    /**
     * @return View
     */
    public function profile(): View
    {
        return view('dashboard.profile');
    }


    /**
     * @param Request $request
     * @return View
     */
    public function showTransactions(Request $request):View
    {
        $user = Customer::with('transactions.records:id,name,price,discount')->find($request->id);

        return view('users.transactions')->with(compact('user'));
    }

}
