<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    public function index()
    {
        $title = 'Data Company';
        $breadcrumbs = ['Master', 'Data Company'];

        return view('company.index', compact('title', 'breadcrumbs'));
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::orderBy('name', 'asc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('total', function ($row) {
                    return $row->devices()->count();
                })
                ->editColumn('logo', function ($row) {
                    return '<img src="' . asset('/storage/' . $row->logo) . '" class="rounded-circle" alt="" width="40">';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route('companies.device') . '?company=' . $row->id . '" class="btn btn-sm btn-info">Show</a> <a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('companies.update', $row->id) . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('companies.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'logo'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'logo' => 'required|mimes:jpg,jpeg,png,gif'
        ]);
        try {
            DB::beginTransaction();

            $logoUrl = $request->file('logo')->storeAs('companies', Str::slug($request->name . '-' . Str::random(8)) . '.' . $request->file('logo')->extension());

            Company::create([
                'name' => $request->name,
                'address' => $request->address,
                'logo' => $logoUrl
            ]);

            DB::commit();

            return back()->with('success', "Company successfully created");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Company $company)
    {
        return response()->json([
            'company' => $company
        ]);
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            if ($request->file('logo')) {
                $company->logo != null ? Storage::delete($company->logo) : '';
                $logoUrl = $request->file('logo')->storeAs('companys', Str::slug($request->name . '-' . Str::random(8)) . '.' . $request->file('logo')->extension());
            } else {
                $logoUrl = $company->logo;
            }

            $company->update([
                'alamat' => $request->alamat,
                'name' => $request->name,
                'logo' => $logoUrl
            ]);

            DB::commit();

            return back()->with('success', "Company successfully updated");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Company $company)
    {
        try {
            DB::beginTransaction();

            $company->foto != null ? Storage::delete($company->foto) : '';
            $company->delete();

            DB::commit();
            return back()->with('success', "Company successfully deleted");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
