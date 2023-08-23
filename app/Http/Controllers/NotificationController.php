<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Notification;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function index()
    {
        $title = 'Notification Setting';
        $breadcrumbs = ['Notification Setting'];
        $devices = Device::get();

        return view('notification.index', compact('title', 'breadcrumbs', 'devices'));
    }

    function get(Request $request)
    {
        if ($request->ajax()) {
            $data = Notification::get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('notifications.update', $row->id) . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('notifications.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Notification $notification)
    {
        //
    }

    public function edit(Notification $notification)
    {
        //
    }

    public function update(Request $request, Notification $notification)
    {
        //
    }

    public function destroy(Notification $notification)
    {
        //
    }
}
