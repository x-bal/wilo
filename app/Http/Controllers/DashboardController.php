<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $breadcrumbs = ['Dashboard'];
        $companies = Company::get();

        return view('dashboard.index', compact('title', 'breadcrumbs', 'companies'));
    }
}
