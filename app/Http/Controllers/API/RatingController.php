<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use App\Models\Review;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{


    public function store(Request $request)
    {

        try {

            $review = Review::where('user_id', Auth::id())
            ->where([
                'vendor_id' => $request->vendor_id, 
                'order_id' => $request->order_id
            ])->first();
            if (!empty($review)) {
                throw new Exception("Vendor already rated", 1);
            }

            $model = new Review();
            $model->user_id = Auth::id();
            $model->vendor_id = $request->vendor_id;
            $model->order_id = $request->order_id;
            $model->rating = $request->rating;
            $model->review = $request->review;
            $model->save();

            //
            $vendor = Vendor::find($request->vendor_id);
            $vendor->rate($request->rating);


            return response()->json([
                "message" => "Vendor rated successfully"
            ], 200);
        } catch (\Exception $ex) {

            return response()->json([
                "message" => "Vendor rating failed"
            ], 400);
        }
    }
}
