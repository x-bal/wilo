<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Device;
use App\Models\Merge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->company) {
            $title = 'Data Device Company';
            $breadcrumbs = ['Master', 'Data Device Company'];
            $company = Company::find($request->company);
            return view('company.device', compact('title', 'breadcrumbs', 'company'));
        } else {
            $title = 'Data Device';
            $breadcrumbs = ['Master', 'Data Device'];
            $companies = Company::get();

            return view('device.index', compact('title', 'breadcrumbs', 'companies'));
        }
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            if ($request->company) {
                $data = Device::where('company_id', $request->company)->get();
            } else {
                $data = Device::get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function ($row) {
                    return '<img src="' . asset('/storage/' . $row->image) . '" class="rounded-circle" alt="" width="40">';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route('devices.detail', $row->id) . '" class="btn btn-sm btn-info">Show</a> <a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('devices.update', $row->id) . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('devices.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->editColumn('company', function ($row) {
                    return $row->company->name;
                })
                ->editColumn('status', function ($row) {
                    return $row->is_active == 1 ? 'Active' : 'Nonactive';
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $attr = $request->validate([
            'company_id' => 'required|numeric',
            'name' => 'required|string',
            'topic' => 'required|string',
            'end_user' => 'required|string',
            'iddev' => 'required|unique:devices',
            'type' => 'required|string',
            'power' => 'required|string',
            'flow' => 'required|string',
            'head' => 'required|string',
            'modbus' => 'required|string',
            'digital' => 'required|string',
            'lat' => 'required|string',
            'long' => 'required|string',
            'image' => 'required|mimes:jpg,jpeg,png',
        ]);

        try {
            DB::beginTransaction();

            $image = $request->file('image');
            $attr['image'] = $image->storeAs('devices', 'dev' . date('Ymd') . rand(1000, 9999) . '.' . $image->extension());

            $device = Device::create($attr);
            $modbuses = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];
            $digital = [1, 2, 3, 4, 5, 6, 7,];

            foreach ($modbuses as $modbus) {
                $device->modbuses()->create([
                    'name' => 'Modbus ' . $modbus,
                ]);
            }

            foreach ($digital as $dig) {
                $device->digitalInputs()->create([
                    'name' => 'Digital Input ' . $dig,
                    'digital_input' => $dig
                ]);
            }

            DB::commit();

            return back()->with('success', 'Device sucessfully created');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Device $device)
    {
        return response()->json([
            'device' => $device
        ]);
    }

    public function update(Request $request, Device $device)
    {
        $attr = $request->validate([
            'company_id' => 'required|numeric',
            'name' => 'required|string',
            'topic' => 'required|string',
            'end_user' => 'required|string',
            'iddev' => 'required|unique:devices,iddev,' . $device->iddev,
            'type' => 'required|string',
            'power' => 'required|string',
            'flow' => 'required|string',
            'head' => 'required|string',
            'modbus' => 'required|string',
            'digital' => 'required|string',
            'lat' => 'required|string',
            'long' => 'required|string',
            'image' => 'mimes:jpg,jpeg,png',
        ]);

        try {
            DB::beginTransaction();

            if ($request->file('image')) {
                Storage::delete($device->image);
                $image = $request->file('image');
                $attr['image'] = $image->storeAs('devices', 'dev' . date('Ymd') . rand(1000, 9999) . '.' . $image->extension());
            } else {
                $attr['image'] = $device->image;
            }

            $device->update($attr);

            DB::commit();

            return back()->with('success', 'Device sucessfully updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Device $device)
    {
        try {
            DB::beginTransaction();
            // foreach ($device->histories as $history) {
            //     $history->delete();
            // }

            foreach ($device->modbuses as $modbus) {
                $modbus->delete();
            }

            foreach ($device->digitalInputs as $digital) {
                $digital->delete();
            }

            $device->delete();

            DB::commit();

            return back()->with('success', 'Device successfully deleted');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function list()
    {

        $first = Device::where('is_active', 1)->first();

        if ($first) {
            $devices = Device::where('is_active', 1)->get();
        } else {
            $devices = '';
        }

        return response()->json([
            'devices' => $devices
        ]);
    }

    function detail(Device $device)
    {
        $title = 'Detail Device ' . $device->name;
        $breadcrumbs = ['Master', 'Detail Device ' . $device->name];

        return view('device.show', compact('title', 'breadcrumbs', 'device'));
    }

    public function find(Device $device)
    {
        $modbus = $device->modbuses()->where('is_used', 1)->get();
        $digital = $device->digitalInputs()->where('is_used', 1)->get();
        $image = asset('/storage/' . $device->image);
        // $history = $device->histories()->latest()->first();
        $merge = Merge::where('device_id', $device->id)->get();

        return response()->json([
            'device' => $device,
            'modbus' => $modbus,
            'digital' => $digital,
            'merge' => $merge,
            'image' => $image,
            'history' =>  0,
        ]);
    }
}
