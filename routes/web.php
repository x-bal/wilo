<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users/get', [UserController::class, 'get'])->name('users.list');
    Route::resource('users', UserController::class);

    Route::get('/companies/get', [CompanyController::class, 'get'])->name('companies.list');
    Route::get('/companies/device', [DeviceController::class, 'index'])->name('companies.device');
    Route::resource('companies', CompanyController::class);

    Route::get('/devices/get', [DeviceController::class, 'get'])->name('devices.get');
    Route::get('/devices/list', [DeviceController::class, 'list'])->name('devices.list');
    Route::get('/devices/{device:id}/detail', [DeviceController::class, 'detail'])->name('devices.detail');
    Route::get('/devices/{device:id}/performance', [DeviceController::class, 'grafik'])->name('devices.grafik');
    Route::resource('devices', DeviceController::class);

    Route::resource('server', ServerController::class);
});
