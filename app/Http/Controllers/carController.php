<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Train;

class carController extends Controller
{
    public function find_class($trip_id)
    {
        // request body
        // the whole trips object
        // get the trains_id from the trips with that id
        $trip = Trip::find($trip_id);
        $train = Train::find($trip->trains_id);

        // getting the car class with this train_id
        $car = Car::find($train->id);
        return $car->class;
    }

    public function store(Request $request)
    {
        // request body
        // class, trains_id validate
        $validated = $request->validate([
            'class' => 'required',
            'trains_id' => 'required'
        ]);

        // if not valid
        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error'
            ], 400);
        }


        $car = Car::create($validated);

        // IF fails
        if (!$car) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create car'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $car
        ]);
    }
}
