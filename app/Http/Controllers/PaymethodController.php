<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

// impelemented
// index, store, destroy
class PaymethodController extends Controller
{
    // get all
    public function index()
    {
        $paymethods = PaymentMethod::all();

        return response()->json([
            'status' => 'success',
            'data' => $paymethods
        ]);
    }

    // post: admin
    public function store(Request $request)
    {
        $request->validate([
            'method_name' => 'required'
        ]);

        $paymethod = PaymentMethod::create($request->all());

        if(!$paymethod) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment method not created'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'data' => $paymethod
        ]);
    }

    // delete: admin
    public function destroy($id)
    {
        $paymethod = PaymentMethod::find($id);

        if(!$paymethod) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment method not found'
            ], 404);
        }

        $paymethod->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment method deleted'
        ]);
    }

}
