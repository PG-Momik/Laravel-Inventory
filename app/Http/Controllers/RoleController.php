<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RoleController extends Controller
{

    /**
     * @return View
     */
    public function index(): View
    {
        $roles = Role::withCount('users')->get();

        return view('roles.index')->with(compact('roles'));
    }


    public function create(): View {}

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
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
     * @param int $id
     * @return Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    public function demote(int $uid)
    {
        $user          = User::findOrFail($uid);
        $user->role_id = $user->role_id + 1;

        return $user->update();
    }

    public function promote(int $uid)
    {
        $user          = User::findOrFail($uid);
        $user->role_id = $user->role_id - 1;

        return $user->update();
    }
}
