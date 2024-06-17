<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\Train;
use App\Models\Car;
use App\Models\Trip;

class SeatController extends Controller
{
    public function choose(Request $request, $trip_id, $seat_id)
    {
        $trip = Trip::find($trip_id);

        if (!$trip) {
            return response()->json([
                'status' => 'error',
                'message' => 'Trip not found'
            ], 404);
        }

        // finding train by trip id
        $train = Train::find($trip->trains_id);

        // finding car by train id
        $car = Car::where('trains_id', $train->id)->first();


        // finding seats by car id and seat id
        $seat = Seat::where('cars_id', $car->id)->where('id', $seat_id)->first();

        if (!$seat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seat not found'
            ], 404);
        }

        // check if seat is available
        if ($seat->is_available == false) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seat is not available'
            ], 404);
        }

        // update seat is_available to false
        $seat->update([
            'is_available' => false
        ]);

        return $seat;
    }

    public function choose_random(Request $request, $trip_id)
    {
        $trip = Trip::find($trip_id);

        if (!$trip) {
            return response()->json([
                'status' => 'error',
                'message' => 'Trip not found'
            ], 404);
        }

        // finding train by trip id
        $train = Train::find($trip->trains_id);

        // finding car by train id
        $car = Car::where('trains_id', $train->id)->first();


        // finding seats by car id and seat id
        $seat = Seat::where('cars_id', $car->id);

        if (!$seat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seat not found'
            ], 404);
        }

        // getting random seat
        $seat = $seat->inRandomOrder()->first();

        // update seat is_available to false
        $seat->update([
            'is_available' => false
        ]);

        return $seat;
    }
}
