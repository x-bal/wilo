<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    function index()
    {
        $title = 'Role Management';
        $breadcrumbs = ['Role Management'];
        $permissions = Permission::orderBy('name', 'ASC')->get();

        return view('roles.index', compact('title', 'breadcrumbs', 'permissions'));
    }

    function get(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::orderBy('name', 'asc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('roles.update', $row->id) . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('roles.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'foto'])
                ->make(true);
        }
    }

    function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'permissions' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            $role->syncPermissions($request->permissions);

            DB::commit();

            return back()->with('success', "Role successfully created");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    function show(Role $role)
    {
        return response()->json([
            'role' => $role,
            'permissions' => DB::table('role_has_permissions')->where('role_id', $role->id)->pluck('permission_id')
        ]);
    }

    function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string',
            'permissions' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            $role->update([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            $role->syncPermissions($request->permissions);

            DB::commit();

            return back()->with('success', "Role successfully updated");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    function destroy(Role $role)
    {
        try {
            DB::beginTransaction();

            $role->delete();

            DB::commit();

            return back()->with('success', "Role successfully deleted");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
