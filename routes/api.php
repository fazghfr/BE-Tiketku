<?php

use App\Http\Controllers\trainController;
use App\Http\Controllers\tripController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PaymethodController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


// defining the routes for the trains by making the group first
// NOTES in advance : trains, trips store and destroy is admin only
Route::group(['prefix' => 'trains'], function () {
    Route::get('/', [trainController::class, 'index']);
    Route::get('/{id}', [trainController::class, 'show']);
    Route::post('/', [trainController::class, 'store']);
    Route::delete('/{id}', [trainController::class, 'destroy']);
});

// defining the routes for the trips by making the group first
Route::group(['prefix' => 'trips'], function () {
    Route::get('/', [tripController::class, 'index']);
    Route::get('/{id}', [tripController::class, 'show']);
    Route::post('/', [tripController::class, 'store']);
    Route::delete('/{id}', [tripController::class, 'destroy']);
});

// defining the routes for users
// NOTES in advance : users delete, update and get its own needed to be authenticated
// get all is admin only
Route::group(['prefix' => 'users'], function () {
    Route::get('/', [UsersController::class, 'index']);
    Route::get('/{id}', [UsersController::class, 'show']);
    Route::post('/', [UsersController::class, 'store']);
    Route::put('/{id}', [UsersController::class, 'update']);
    Route::delete('/{id}', [UsersController::class, 'destroy']);
});

// defining the routes for the payment methods
// payment post and deletion is admin only
Route::group(['prefix' => 'paymethods'], function () {
    Route::get('/', [PaymethodController::class, 'index']);
    Route::post('/', [PaymethodController::class, 'store']);
    Route::delete('/{id}', [PaymethodController::class, 'destroy']);
});


// defining the routes for the transactions
// NOTES in advance : transactions index is admin only
Route::group(['prefix' => 'transactions'], function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::get('/{id}', [TransactionController::class, 'show']);
    Route::post('/', [TransactionController::class, 'store']);
});
