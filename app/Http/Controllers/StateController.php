<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StateController extends Controller
{
    function loadStates(Request $request){

        try {
            
            $country_id = $request->country_id;
            $states = State::where('country_id' , $country_id)->with('country')->get();
            return response()->json(['success' => true , 'data' => $states] , 200);

        } catch (\Throwable $th) {

            return response()->json(['success' => false , 'message' => $th->getMessage()] , 500);
        }

    }
}
