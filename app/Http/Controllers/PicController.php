<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Train;
use App\Models\Picture;

class PicController extends Controller
{
    public function show($trainid)
    {
        // getting the train data by id
        $train = Train::find($trainid);

        // if the train data is not found
        if (!$train) {
            return response()->json([
                'status' => 'error',
                'message' => 'Train not found'
            ], 404);
        }

        // getting all the pictures data by train id
        $picture = Picture::where('trains_id', $trainid)->get();

        // returning the json response
        return $picture;
    }
}
