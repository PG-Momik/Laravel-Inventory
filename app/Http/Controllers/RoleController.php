<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }
    /**
     * Returns role.index as view
     *
     */
    public function index()
    {
        $roles = Role::withCount('users')->get();

        return view('roles.index')->with(compact('roles'));
    }


    /**
     * @return void
     */
    public function create(): void {}

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     *
     * @return View
     */
    public function show(Role $role): View
    {
        $role->load('users');

        return view('roles.role')->with(compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     *
     * @return Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Role $role
     *
     * @return void
     */
    public function update(Request $request, $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(Role $role)
    {
        //
    }


    /**
     * Returns true if user is demoted
     *
     * @param int $uid
     *
     * @return bool
     */
    public function demote(int $uid): bool
    {
        $user = User::findOrFail($uid);
        $user->removeRole('Admin');
        $user->assignRole('User');

        return $user->update();
    }

    /**
     * Returns true if user is promoted
     *
     * @param int $uid
     *
     * @return mixed
     */
    public function promote(int $uid): bool
    {
        $user = User::findOrFail($uid);
        $user->removeRole('User');
        $user->assignRole('Admin');

        return $user->update();
    }

    /**
     * Returns json value needed for roles.role bar graph
     *
     * @return JsonResponse
     */
    public function getRoleBasedStats(): JsonResponse
    {
        $numberOfAdmins     = Role::withCount('users')->find(1)->users_count;
        $numberOfNonAdmin   = Role::withCount('users')->find(2)->users_count;
        $totalNumberOfUsers = $numberOfAdmins + $numberOfNonAdmin;

        return response()->json(
            [
                'totalNumberOfUsers' => $totalNumberOfUsers,
                'numberOfAdmins'     => $numberOfAdmins,
                'numberOfNonAdmin'   => $numberOfNonAdmin
            ]
        );
    }
}
