<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

// implemented
// index, show, store, update, destroy
class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'gender' => 'required'
        ]);
        // check if email already exists
        $user = User::where('email', $request->email)->first();
        if($user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email already exists'
            ], 409);
        }

        $user = User::create($request->all());

        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not created'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User created',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        // getting the existing data of that user
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'], 404);
        }

        // validate
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'gender' => 'sometimes|string|max:32',
        ]);

        // Update the user's attributes
        $user->update($validatedData);

        // Return the updated user
        return response()->json([
            'status' => 'success',
            'message' => 'User updated',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted'
        ]);
    }
}
