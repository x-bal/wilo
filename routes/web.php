<?php

use App\Http\Controllers\AccessViewerController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ModbusController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;
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

    Route::post('/modbus-setting', [ModbusController::class, 'setting'])->name('modbus.setting');
    Route::post('/modbus/merge', [ModbusController::class, 'merge'])->name('modbus.merge');
    Route::post('/merge/{merge:id}', [ModbusController::class, 'deleteMerge'])->name('merge.delete');
    Route::resource('modbus', ModbusController::class);

    Route::get('/roles/get', [RoleController::class, 'get'])->name('roles.get');
    Route::resource('roles', RoleController::class);

    Route::resource('server', ServerController::class);

    Route::get('/access-viewer', [AccessViewerController::class, 'index'])->name('access.index');
    Route::get('/access-viewer/list', [AccessViewerController::class, 'list'])->name('access.list');
    Route::get('/access-viewer/{id}', [AccessViewerController::class, 'show'])->name('access.show');
    Route::post('/access-viewer/store', [AccessViewerController::class, 'store'])->name('access.store');
    Route::delete('/access-viewer/{id}', [AccessViewerController::class, 'destroy'])->name('access.destroy');

    Route::get('/notifications/get', [NotificationController::class, 'get'])->name('notifications.get');
    Route::resource('notifications', NotificationController::class);
});
