<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccessViewerController extends Controller
{
    public function index()
    {
        $title = 'Access Viewer';
        $breadcrumbs = ['Access Viewer'];
        $users = User::get();
        $devices = Device::get();

        return view('access.index', compact('title', 'breadcrumbs', 'users', 'devices'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('devices');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('device', function ($row) {
                    $devices = "";
                    foreach ($row->devices as $device) {
                        $devices .= '<button type="button" class="btn btn-sm btn-secondary">' . $device->name . '</button> ';
                    }
                    return $devices;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('access.store') . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('access.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'device'])
                ->make(true);
        }
    }

    function store(Request $request)
    {
        $request->validate([
            'user' => 'required|numeric',
            'device' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            // return $request->all();
            $user = User::find($request->user);

            $user->devices()->sync($request->device);

            DB::commit();

            return back()->with('success', "Access successfully added");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    function show($id)
    {
        $user = User::with('devices')->find($id);

        return response()->json([
            'access' => $user,
            'devices' => $user->devices()->pluck('id')
        ]);
    }

    function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);

            $user->devices()->detach();

            DB::commit();
            return back()->with('success', "Access successfully deleted");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
