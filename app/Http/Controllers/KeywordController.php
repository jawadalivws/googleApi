<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keyword;
use App\Models\KeywordRecord;
use Illuminate\Support\Facades\Http;
use Goutte;
use Rap2hpoutre\FastExcel\FastExcel;
use GuzzleHttp\Client;

class KeywordController extends Controller
{

    public function index()
    {
        $records = [];
        $keywords = Keyword::paginate(10);
        $scanned_ids = KeywordRecord::pluck('keyword_id')->groupBy('keyword_id')->first();

        if($scanned_ids != null){

            $records = Keyword::whereIn('id', $scanned_ids)->get();

        }
        return view('dashboard', ['keywords' => $keywords , 'records' => $records]);
    }
    public function addKeyword(Request $request)
    {
  
        $request->validate([
            'keyword' => 'required|unique:keywords,name',
        ]);
        
        $insert = Keyword::insert([
            'name' => $request->keyword,
        ]);

        return back()->with('success' , 'Word Added Successfully.');
        // for ajax response
        // if($insert){
        //     $data['success'] = true;
        //     $data['message'] = 'Word Added Successfully.';
        //     return response()->json($data);
        // }
    }

    public function deleteKeyword($id)
    {
        $exist = Keyword::where('id' , $id)->first();

        if($exist)
        {
            $delete = Keyword::where('id' , $id)->delete();

            if($delete)
            {
                $data['success'] = true;
                $data['message'] = 'Word Added Successfully.';
                return response()->json($data);
            }

            return response()->json('error' , 'Something went wrong');

        }
    }
    
    public function keywordDetail($id)
    {
        $keyword = Keyword::where('id' , $id)->first();
        
        $client = new Client();
        $response = $client->get('https://example.com/api/resource');
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true); 
        
        if(isset($keyword->id)){
            // dd($keyword->keyword_records);
            return view('keyword_detail' , ['keyword' => $keyword]);
            
        }else{
            
            return back()->with('error' , 'Keyword not found.');
            
        }
    }
    
    public function deleteEmail($id)
    {
        $exist = KeywordRecord::where('id' , $id)->first();
    
        if($exist)
        {
            $delete = Keyword::where('id' , $id)->delete();
    
            if($delete)
            {
                return response()->json('success' , 'Email deleted successfully.');
            }

            return response()->json('error' , 'Something went wrong');
        }
    }

    public function export(Request $request)
    {

        if($request->keyword == ''){
           
            $data = KeywordRecord::select('title' , 'email')->get();
        
        }else{

            $data = KeywordRecord::where('keyword_id' , $request->keyword)->select('title' , 'email')->get();

        }
        
        return (new FastExcel($data))->download('file.xlsx');

    }

}
