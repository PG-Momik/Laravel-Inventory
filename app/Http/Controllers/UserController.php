<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $searchKeyword = $request['search-field'] ?? '';

        if ( empty($searchKeyword) ) {
            $users = User::select('users.id', 'users.name', 'users.role_id', 'users.email')
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

        return view('users.index')->with(compact('users', 'searchKeyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('users.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(apply_validation_to(['name', 'email', 'role_id', 'password']));
        $user           = new User();
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

        return redirect()->route('users.create');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return RedirectResponse| View
     */
    public function show($id): RedirectResponse | View
    {
        $user = User::with('roles:id,name')
            ->with('productEntries')
            ->withCount('transactions')
            ->find($id);

        if ( is_null($user) ) {
            return redirect('/users');
        }

        return view('users.user')->with(compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): view
    {
        $user = User::find($id);

        return view('users.edit')->with(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate(apply_validation_to(['name', 'email', 'role_id'], 'update'));

        $user          = User::find($id);
        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->role_id = $request->role_id;

        if ( $request->verifyEmail == "false" ) {
            $user->email_verified_at = null;
        }
//        if($user->email_verified_at == NULL){
//            $user->email_verified_at = now()->format('Y:m:d H:i:s');
//        }

        try {
            $user->update();
        } catch ( Exception $e ) {
            session()->flash('warning', "Something went wrong.");

            return redirect()->route('users.show', ['id' => $user->id]);
        }
        session()->flash('success', "User info updated.");

        return redirect()->route('users.show', ['user' => $user->id]);
    }


    /**
     * @return View
     */
    public function profile(): View
    {
        return view('dashboard.profile');
    }


    /**
     * Shows User Transactions
     * @param Request $request
     *
     * @return View
     */
    public function showTransactions(Request $request): View
    {
        $user = User::with('transactions.records:id,name,price,discount')->find($request->id);

        return view('users.transactions')->with(compact('user'));
    }

    /**
     * Shows Trashed Data
     * @param Request $request
     * @return View
     */
    public function showTrash(Request $request): View
    {
        $searchKeyword = $request['search-field'] ?? '';
        if ( empty($searchKeyword) ) {
            $users = User::onlyTrashed()->paginate(10);
        } else {
            $users = User::onlyTrashed()
                ->where('users.name', 'LIKE', "%$searchKeyword%")
                ->orWhere('users.email', 'LIKE', "%$searchKeyword%")
                ->paginate(10);
        }

        return view('users.trashed')->with(compact('users', 'searchKeyword'));
    }


    /**
     * Moves user to trash
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $user->delete();
            session()->flash('warning', 'User moved to trash');

            return redirect()->back();
        } catch ( Exception $e ) {
            session()->flash('warning', 'Something went wrong. Try Again.');
        }

        return redirect()->route('users.trashed');
    }

    /**
     * Restore trashed user
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function restore(int $id): RedirectResponse
    {
        try {
            $user = User::withTrashed()->find($id);
            $user->restore();
            session()->flash('success', "User restored");
        } catch ( Exception $e ) {
            session()->flash('warning', 'Something went wrong.');
        }

        return redirect()->back();
    }


    /**
     * Remove user from db
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function hardDelete(int $id): RedirectResponse
    {
        try {
            $user = User::withTrashed()->find($id);
            $user->forceDelete();
            session()->flash('success', 'User record destroyed.');
        } catch ( Exception $e ) {
            session()->flash('warning', "Something went wrong. Try again.");
        }

        return redirect()->back();
    }


}
