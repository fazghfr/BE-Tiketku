<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Train;

class TrainController extends Controller
{
    public PicController $PicController;

    public function __construct(PicController $PicController)
    {
        $this->PicController = $PicController;
    }

    public function index()
    {
        // getting all trains data from database using the model
        $trains = Train::all();

        // returning the json response
        return response()->json([
            'status' => 'success',
            'data' => $trains
        ]);
    }

    public function show($id)
    {
        // getting the train data by id
        $train = Train::find($id);

        // if the train data is not found
        if (!$train) {
            return response()->json([
                'status' => 'error',
                'message' => 'Train not found'
            ], 404);
        }

        // finding pictures of that train
        $pictures = $this->PicController->show($train->id);

        $response_object = [
            'train' => $train,
            'pictures' => $pictures
        ];

        // returning the json response
        return response()->json([
            'status' => 'success',
            'data' => $response_object
        ]);
    }

    public function store(Request $request)
    {
        // validating the request data
        $request->validate([
            'name' => 'required',
            'car_count' => 'required',
            'seat_per_car_count' => 'required'
        ]);

        // creating a new train data
        $train = Train::create([
            'name' => $request->name,
            'car_count' => $request->car_count,
            'seat_per_car_count' => $request->seat_per_car_count
        ]);

        // returning the json response is there is an error
        if (!$train) {
            return response()->json([
                'status' => 'error',
                'message' => 'Train not created'
            ], 400);
        }

        // returning the json response
        return response()->json([
            'status' => 'success',
            'data' => $train
        ], 201);
    }

    public function destroy($id)
    {
        // getting the train data by id
        $train = Train::find($id);

        // if the train data is not found
        if (!$train) {
            return response()->json([
                'status' => 'error',
                'message' => 'Train not found'
            ], 404);
        }

        // deleting the train data
        $train->delete();

        // returning the json response
        return response()->json([
            'status' => 'success',
            'message' => 'Train deleted'
        ]);
    }
}
