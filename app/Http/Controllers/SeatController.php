<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\Train;
use App\Models\Car;
use App\Models\Trip;

// implemnted
// show_by_trip, choose, choose_random, store, batch_store
class SeatController extends Controller
{
    public function show_by_trip($trip_id)
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
        $car = Car::where('trains_id', $train->id)->get();

        // for each car get all seats, the return data would be per car
        /*
        [car_1 => [seat_1, seat_2, seat_3], car_2 => [seat_1, seat_2, seat_3]]

        */

        $seats = [];
        foreach ($car as $c) {
            $seat = Seat::where('cars_id', $c->id)->get();
            $seats[$c->id] = $seat;
        }

        return response()->json([
            'status' => 'success',
            'data' => $seats
        ]);
    }

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
        if ($seat->is_taken == true) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seat is not available'
            ], 404);
        }

        // update seat is_available to false
        $seat->update([
            'is_taken' => false
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

        // getting random seat that is available
        $seat = $seat->where('is_taken', false)->inRandomOrder()->first();

        return $seat;

        // update seat is_available to false
        $seat->update([
            'is_taken' => true
        ]);

        return $seat;
    }

    public function store(Request $request)
    {
        $request->validate([
            'seat_number' => 'required',
            'is_available' => 'required',
            'cars_id' => 'required'
        ]);

        $seat = Seat::create($request->all());

        if (!$seat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seat not created'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'data' => $seat
        ]);
    }

    public function batch_store(Request $request)
    {

        $request->validate([
            'num_seats' => 'required',
            'cars_id' => 'required'
        ]);

        // calling store method for each seat
        // with code_position starting is like this
        // imagine we have ABCD column
        // and each row is a number
        // so first row is 1A, 1B, 1C, 1D
        // second row is 2A, 2B, 2C, 2D
        // and so on

        // num_seats always can be divided by 4
        // because we have 4 columns
        for ($i = 0; $i < $request->num_seats; $i++) {
            $column = $i % 4;
            $row = floor($i / 4) + 1;
            $code_position = $row . chr(65 + $column); // Convert column number to corresponding letter (A, B, C, D)

            $seat = Seat::create([
            'code_position' => $code_position,
            'is_taken' => false,
            'cars_id' => $request->cars_id
            ]);

            if (!$seat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seat not created'
            ], 500);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Seats created'
        ]);

    }
}
