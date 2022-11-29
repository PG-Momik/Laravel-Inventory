<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @param SearchRequest $request
     *
     * @return View
     */

    public function index(SearchRequest $request): View
    {
        $searchKeyword = $request->validated('search-field') ?? '';

        $users = User::when(
            !empty($searchKeyword),
            function ($users) use ($searchKeyword) {
                return $users
                    ->where('name', 'like', "%$searchKeyword%")
                    ->orWhere('email', 'like', "%$searchKeyword%");
            }
        )->withCount('transactions')
            ->with('roles')
            ->paginate(10);

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
     * @param CreateUserRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        $user           = new User();
        $user->name     = $request->validated('name');
        $user->email    = $request->validated('email');
        $user->password = $request->password ?? Hash::make('Password#123');
        try {
            $user->save();
            $user->assignRole($request->validated('role'));

            session()->flash('success', 'User added successfully.');
        } catch (Exception) {
            session()->flash('danger', 'Something went wrong.');
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return RedirectResponse| View
     */
    public function show(User $user): RedirectResponse | View
    {
        $user->load('roles:id,name', 'registeredProducts', 'transactions');

        return view('users.user')->with(compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     *
     * @return View
     */
    public function edit(User $user): view
    {
        $user->load('roles:id,name');

        return view('users.edit')->with(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if ($request->verified === "unverified") {
            $user->email_verified_at = null;
        }
        if ($request->verified == "verified") {
            $user->email_verified_at = Carbon::now()->format('Y-m-i H:m:s');
        }

        try {
            $user->name  = $request->validated('name');
            $user->email = $request->validated('email');
            $user->update();

            //Removes user role and reassigns role from request.
            //Since 1 user has only 1 role, first() captures the role.
            $user->roles = $request->validated('role');
            $user->removeRole($user->roles()->first());
            $user->assignRole($request->role);

            session()->flash('success', "User info updated.");
        } catch (Exception) {
            session()->flash('warning', "Something went wrong.");
        }

        return redirect()->back();
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
     *
     * @param Request $request
     *
     * @return View
     */
    public function showTransactions(Request $request): View
    {
        $user = User::find($request['id']);

        $transactions = Transaction::where('user_id', $request['id'])
            ->with('product')
            ->with('salesPriceDuringTransaction')
            ->with('purchasePriceDuringTransaction')
            ->paginate(15);

        return view('users.transactions')
            ->with(
                [
                    'user'         => $user,
                    'transactions' => $transactions
                ]
            );
    }

    /**
     * Shows Trashed Data
     *
     * @param SearchRequest $request
     *
     * @return View
     */
    public function showTrash(SearchRequest $request): View
    {
        $searchKeyword = $request->validated('search-field') ?? '';
        if (empty($searchKeyword)) {
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
     *
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $user->delete();
            session()->flash('warning', 'User moved to trash');
        } catch (Exception) {
            session()->flash('warning', 'Something went wrong. Try Again.');
        }

        return redirect()->route('users.index');
    }

    /**
     * Restore trashed user
     *
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
        } catch (Exception $e) {
            session()->flash('warning', 'Something went wrong.');
        }

        return redirect()->back();
    }


    /**
     * Remove user from db
     *
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
        } catch (Exception $e) {
            session()->flash('warning', "Something went wrong. Try again.");
        }

        return redirect()->back();
    }


    /**
     * FOR AJAX REQ
     *Returns true if layout toggle based on layout is successful for user
     *
     * @param User $user
     * @param int $layout
     *
     * @return bool
     */
    public function toggleLayout(User $user, int $layout = 1): bool
    {
        $user->layout = $layout;

        return $user->update();
    }

    /**
     * FOR AJAX REQ
     *Returns true if layout toggle based on layout is successful for user
     *
     * @param $id
     *
     * @return bool
     */
    public function markAsRead($id): bool
    {
        $notification = Notification::find($id);

        return $notification->markAsRead();
    }
}
