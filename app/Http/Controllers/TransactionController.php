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
            'trips_id' => 'required',
            'payment_method' => 'sometimes',
            'email' => 'required',
            'phone' => 'required',
            'name' => 'required'
        ]);

        // check if users_id with given email and trips_id is exist
        $user = User::where('email', $request->email)->first();
        if(!$user) {
            $user = User::create([
                'email' => $request->email,
                'phone' => $request->phone,
                'name' => $request->name
            ]);
        } else {
            // update user phone and name
            $user->phone = $request->phone;
            $user->name = $request->name;
        }

        // debug code
        // return response()->json([
        //     'status' => 'error',
        //     'data' => $user
        // ], 404);
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
            'users_id' => $user->id,
            'trips_id' => $request->trips_id,
            'payment_method' => $request->payment_method
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

    public function confirm_pay($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }

        $transaction->is_paid = true;
        $transaction->save();

        return response()->json([
            'status' => 'success',
            'data' => $transaction
        ]);
    }

}
