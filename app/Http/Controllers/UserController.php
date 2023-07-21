<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $title = 'Data User';
        $breadcrumbs = ['Master', 'Data User'];

        return view('user.index', compact('title', 'breadcrumbs'));
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            $data = User::orderBy('name', 'asc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('foto', function ($row) {
                    return '<img src="' . asset('/storage/' . $row->foto) . '" class="rounded-circle" alt="" width="40">';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('users.update', $row->id) . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('users.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'foto'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users',
            'name' => 'required|string',
            'password' => 'required|string|min:6',
            'foto' => 'required|mimes:jpg,jpeg,png,gif'
        ]);
        try {
            DB::beginTransaction();

            $fotoUrl = $request->file('foto')->storeAs('users', Str::slug($request->name . '-' . Str::random(8)) . '.' . $request->file('foto')->extension());

            User::create([
                'email' => $request->email,
                'name' => $request->name,
                'password' => bcrypt($request->password),
                'foto' => $fotoUrl
            ]);

            DB::commit();

            return back()->with('success', "User successfully created");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(User $user)
    {
        return response()->json([
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'email' => 'required|unique:users,email,' . $user->id,
            'name' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        try {
            DB::beginTransaction();

            if ($request->file('foto')) {
                $user->foto != null ? Storage::delete($user->foto) : '';
                $fotoUrl = $request->file('foto')->storeAs('users', Str::slug($request->name . '-' . Str::random(8)) . '.' . $request->file('foto')->extension());
            } else {
                $fotoUrl = $user->foto;
            }

            $user->update([
                'email' => $request->email,
                'name' => $request->name,
                'password' => $request->password != null ? $user->password : bcrypt($request->password),
                'foto' => $fotoUrl
            ]);

            DB::commit();

            return back()->with('success', "User successfully updated");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            $user->foto != null ? Storage::delete($user->foto) : '';
            $user->delete();

            DB::commit();
            return back()->with('success', "User successfully deleted");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
