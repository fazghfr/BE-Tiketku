<?php

use App\Http\Controllers\trainController;
use App\Http\Controllers\tripController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PaymethodController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\carController;
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
    Route::post('/util/find', [tripController::class, 'find']);
    Route::get('/util/getStations', [tripController::class, 'getStations']);
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

// defining the routes for the tickets
// NOTES in advance : tickets destroy is admin only
Route::group(['prefix' => 'tickets'], function () {
    Route::get('/{id}', [TicketController::class, 'show']);
    Route::post('/', [TicketController::class, 'store']);
    Route::delete('/{id}', [TicketController::class, 'destroy']);
    Route::get('/util/getTickets/{email}', [TicketController::class, 'getTickets']);
});

// defining the routes for the cars
Route::group(['prefix' => 'cars'], function () {
    Route::post('/', [carController::class, 'store']);
});


// defining the routes for the transactions
route::group(['prefix' => 'transactions'], function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::get('/{id}', [TransactionController::class, 'show']);
    Route::post('/', [TransactionController::class, 'store']);
});

// ticket
route::group(['prefix' => 'tickets'], function () {
    Route::get('/{id}', [TicketController::class, 'show']);
    Route::post('/', [TicketController::class, 'store']);
    Route::delete('/{id}', [TicketController::class, 'destroy']);
    Route::post('/util/getTickets', [TicketController::class, 'getTickets']);
});

// seats show_by_trip, choose, choose_random, store, batch_store
Route::group(['prefix' => 'seats'], function () {
    Route::get('/{trip_id}', [SeatController::class, 'show_by_trip']);
    Route::post('/{trip_id}/{seat_id}', [SeatController::class, 'choose']);
    Route::post('/{trip_id}', [SeatController::class, 'choose_random']);
    Route::post('/', [SeatController::class, 'store']);
    Route::post('/util/load/batch', [SeatController::class, 'batch_store']);
});
