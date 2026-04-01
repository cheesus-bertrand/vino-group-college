<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('web')->group(function () {
    Route::post('/login', [AuthController::class, 'store'])->name('login');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/csrf-token', function () {
        return response()->json(['csrf_token' => csrf_token()]);
    });
});


