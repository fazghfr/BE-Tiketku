<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Train;
use Illuminate\Http\Request;

// Implemented
// index, show, store, destroy
class TripController extends Controller
{
    // show all trips
    public function index()
    {
        // getting all trips data from database using the model
        $trips = Trip::all();

        // returning the json response
        return response()->json([
            'status' => 'success',
            'data' => $trips
        ]);
    }

    // show a single trip
    public function show($id)
    {
        // getting the trip data by id
        $trip = Trip::find($id);

        // if the trip data is not found
        if (!$trip) {
            return response()->json([
                'status' => 'error',
                'message' => 'Trip not found'
            ], 404);
        }

        // returning the json response
        return response()->json([
            'status' => 'success',
            'data' => $trip
        ]);
    }

    // store a new trip with given id of a train
    public function store(Request $request)
    {
        // validating the request data
        $request->validate([
            'src_station' => 'required',
            'dst_station' => 'required',
            'dpt_sched' => 'required',
            'arv_sched' => 'required',
            'trains_id' => 'required'
        ]);

        // getting the train data by id
        $train = Train::find($request->trains_id);

        // if the train data is not found
        if (!$train) {
            return response()->json([
                'status' => 'error',
                'message' => 'Train not found'
            ], 404);
        }

        // creating a new trip data
        $trip = Trip::create([
            'src_station' => $request->src_station,
            'dst_station' => $request->dst_station,
            'dpt_sched' => $request->dpt_sched,
            'arv_sched' => $request->arv_sched,
            'trains_id' => $request->trains_id
        ]);

        // returning the json response is there is an error
        if (!$trip) {
            return response()->json([
                'status' => 'error',
                'message' => 'Trip could not be created'
            ], 500);
        }

        // returning the json response
        return response()->json([
            'status' => 'success',
            'data' => $trip
        ]);
    }

    public function destroy($id)
    {
        // getting the trip data by id
        $trip = Trip::find($id);

        // if the trip data is not found
        if (!$trip) {
            return response()->json([
                'status' => 'error',
                'message' => 'Trip not found'
            ], 404);
        }

        // deleting the trip data
        $trip->delete();

        // returning the json response
        return response()->json([
            'status' => 'success',
            'message' => 'Trip deleted'
        ]);
    }

}
