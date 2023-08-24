<?php

use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DigitalInputController;
use App\Http\Controllers\ModbusController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/modbus', [ModbusController::class, 'update']);
Route::get('/digital', [DigitalInputController::class, 'update']);
Route::get('/get-history/{device:id}', [DeviceController::class, 'history']);
Route::get('/get-history-modbus/{device:id}', [DeviceController::class, 'historyModbus']);
Route::get('/device/active', [DeviceController::class, 'active']);
Route::get('/math', [ModbusController::class, 'math']);
Route::get('/merge/change', [ModbusController::class, 'change']);
Route::get('/merge/math', [ModbusController::class, 'math']);
Route::get('/merge', [ModbusController::class, 'updateMerge']);

Route::get('/devices/{device:id}', [DeviceController::class, 'find']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('restart', function () {
    $command = 'mqtt:subs'; // Replace with your actual console command

    // Execute the command as a new process
    $command = 'php ' . base_path('artisan') . ' mqtt:subs > /dev/null 2>&1 &';
    $process = Process::fromShellCommandline($command);
    $process->run();

    return response()->json(['message' => 'Command restarted.']);
});
