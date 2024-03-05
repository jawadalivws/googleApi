<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    //

    public function index(Request $request){

        $campaigns = Setting::latest()->paginate('10');

        return view('campaign.index' , compact('campaigns'));
    }

    public function store(Request $request)
    {
        $request->validate(['campaign_id' => 'required']);

        Setting::create([
            'campaign_id' => $request->campaign_id,
        ]);

        return redirect()->route('setting')->with('success' , 'Campaign ID Added successfully.');
    }

    public function update(Request $request)
    {
        $id = $request->id;

        $campaign_id = Setting::where('id' , $id)->first();

        if(isset($campaign_id)){

            $request->validate(['campaign_id' => 'required']);

            Setting::where('id' , $id)->update([
                'campaign_id' => $request->campaign_id,
            ]);

            return redirect()->route('setting')->with('success', 'Campaign ID Updated successfully.');
        }else{

            return redirect()->route('setting')->with('error' , 'Campaign ID not found.');
        }
        
    }
}
