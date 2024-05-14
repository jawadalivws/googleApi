<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function loadCities(Request $request)
    {
        try {

            $id = $request->state_id;

            $cities = City::where('state_id' , $id)->with('state')->get();
    
            return response()->json(['success' => true , 'data' => $cities],200);

        } catch (\Throwable $th) {

            return response()->json(['success' => false , 'message' => $th->getMessage()],500);
        }

    }
}
