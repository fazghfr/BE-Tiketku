<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Train;
use Illuminate\Http\Request;

// Implemented
// index, show, store, destroy
class TripController extends Controller
{
    public carController $carController;

    public function __construct(carController $carController)
    {
        $this->carController = $carController;
    }

    // show all trips
    public function index()
    {
        // getting all trips data from database using the model
        $trips = Trip::all();

        // response obj will be
        // kereta, kelas, stasiunAwal, stasiunAkhir, waktuAwal, waktuAkhir, totalWaktu, harga

        // looping through each trip data to get the train name and car class
        $response_trip = [];
        foreach ($trips as $trip) {
            // getting the train data by id
            $train = Train::find($trip->trains_id);

            // if the train data is not found
            if (!$train) {
            return response()->json([
                'status' => 'error',
                'message' => 'Train not found'
            ], 404);
            }

            // getting the car class data by id
            $train_name = $train->name;
            $car_class = $this->carController->find_class($trip->id);

            if(!$car_class) {
            return response()->json([
                'status' => 'error',
                'message' => 'Car class not found'
            ], 404);
            }

            // kereta, kelas, stasiunAwal, stasiunAkhir, waktuAwal, waktuAkhir, totalWaktu, harga
            // make a new custom object with the train name and car class appended after the trip data
            $custom_object = [
                'trip_id' => $trip->id,
                'kereta' => $train_name,
                'kelas' => $car_class,
                'stasiunAwal' => $trip->src_station,
                'stasiunAkhir' => $trip->dst_station,
                'waktuAwal' => $trip->dpt_sched,
                'waktuAkhir' => $trip->arv_sched,
                'totalWaktu' => 2,
                'harga' => $trip->price
            ];

            // adding the trip data to the response_trip array
            array_push($response_trip, $custom_object);
        }

        // returning the json response
        return response()->json([
            'status' => 'success',
            'data' => $response_trip
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

    // find by src_station and dst_station and dpt_sched
    public function find(Request $request)
    {
        // validating the request data
        $validated = $request->validate([
            'src_station' => 'required',
            'dst_station' => 'required',
            'dpt_sched' => 'sometimes'
        ]);

        // if validate fails
        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data'
            ], 400);
        }

        // getting the trip data by src_station, dst_station and dpt_sched
        if($request->dpt_sched) {
            $trip = Trip::where('src_station', $request->src_station)
                ->where('dst_station', $request->dst_station)
                ->where('dpt_sched', $request->dpt_sched)
                ->first();
        } else {
            $trip = Trip::where('src_station', $request->src_station)
                ->where('dst_station', $request->dst_station)
                ->first();
        }

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
            'trains_id' => 'required',
            'price' => 'required'
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
            'trains_id' => $request->trains_id,
            'price' => $request->price
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

    // getting all distinct src_station and dst_station
    public function getStations()
    {
        // getting all distinct src_station
        $src_stations = Trip::distinct()->get('src_station');

        // getting all distinct dst_station
        $dst_stations = Trip::distinct()->get('dst_station');

        // put all the stations into one array. so
        // example : stations = [station1, station2, station3, station4]
        //loop through each
        $stations = [];
        foreach ($src_stations as $src_station) {
            array_push($stations, $src_station->src_station);
        }

        foreach ($dst_stations as $dst_station) {
            // check if the station is already in the array
            if (in_array($dst_station->dst_station, $stations)) {
                continue;
            }
            array_push($stations, $dst_station->dst_station);
        }

        // returning the json response
        return response()->json([
            'status' => 'success',
            'data' => $stations
        ]);
    }

}
