<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Validator;



class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user->can('manage role')) {
            return redirect()->intended('dashboard');
        }
        $role = Role::all();
        return view('role.main', compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if (!$user->can('manage role')) {
            return redirect()->intended('dashboard');
        }
        $permission = Permission::all();
        return view('role.tambah', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required|unique:roles,name|string",
            "permissions" => "required|array",
            "permissions.*" => "exists:permissions,name"
        ]);

        if ($validate->fails()) {
            return redirect()->route('role.create')->withErrors($validate)->withInput();
        }

        $role = Role::create([
            "name" => $request->name
        ]);

        $role->givePermissionTo($request->permissions);
        return redirect()->route('role.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $role = Role::find($id);
        // $permission = Permission::all();
        // return view('role.edit', compact('role', 'permission'));
        $user = auth()->user();
        if (!$user->can('manage role')) {
            return redirect()->intended('dashboard');
        }
        $role = Role::find($id);
        $permission = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray(); // ambil id permission dari role
        return view('role.edit', compact('role', 'permission', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $target = Role::find($id);
        $validate = Validator::make($request->all(), [
            "name" => "required|unique:roles,name," . $id . "|string",
            "permissions" => "required|array",
            "permissions.*" => "exists:permissions,id"
        ]);


        if ($validate->fails()) {
            return redirect()->route('role.edit', $id)->withErrors($validate)->withInput();
        }

        $target->update([
            "name" => $request->name
        ]);


        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $target->syncPermissions($permissions);

        return redirect()->route('role.index');
        // $target->syncPermissions($request->permissions);
        // return redirect()->route('role.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $target = Role::find($id);
        $target->delete();
        return redirect()->route('role.index');
    }
}
