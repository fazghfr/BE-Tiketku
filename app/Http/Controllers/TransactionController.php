<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Trip;

// implemented
// index, show, store
class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();

        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ]);
    }

    public function show($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $transaction
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'is_paid' => 'required',
            'total_price' => 'required',
            'users_id' => 'required',
            'trips_id' => 'required',
            'payment_methods_id' => 'sometimes'
        ]);

        // check if users_id and trips_id is exist
        $user = User::find($request->users_id);
        $trip = Trip::find($request->trips_id);

        if(!$user || !$trip) {
            return response()->json([
                'status' => 'error',
                'message' => 'User or Trip not Found'
            ], 404);
        }


        $transaction = Transaction::create([
            'is_paid' => $request->is_paid,
            'total_price' => $request->total_price,
            'users_id' => $request->users_id,
            'trips_id' => $request->trips_id,
            'payment_methods_id' => $request->payment_methods_id
        ]);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction failed to store'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'data' => $transaction
        ]);
    }

}
