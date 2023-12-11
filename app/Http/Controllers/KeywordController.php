<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keyword;
use App\Models\KeywordRecord;
use Illuminate\Support\Facades\Http;
use Goutte;
use Rap2hpoutre\FastExcel\FastExcel;
use GuzzleHttp\Client;
use Session;

class KeywordController extends Controller
{

    public function index(Request $request)
    {
        if(isset($request->search) && !empty($request->search)){

            $keyword = $request->search;
            Session::put('keyword', $keyword);
            $keywords = Keyword::where('name' , 'like' , '%'.$keyword.'%')->paginate(10);

        }else{

            if(session('keyword')){
                session()->forget('keyword');
            }
            $keywords = Keyword::paginate(10);
        }

        $records = [];
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

    public function deleteKeyword(Request $request)
    {
        $id = $request->id;
        $exist = Keyword::where('id' , $id)->first();

        if($exist)
        {
            $delete = Keyword::where('id' , $id)->delete();

            if($delete)
            {
                $data['success'] = true;
                $data['message'] = 'Keyword deleted Successfully.';
                return response()->json($data);
            }

            return response()->json('error' , 'Something went wrong');

        }
    }
    
    public function keywordDetail($id)
    {
        $keyword = Keyword::where('id' , $id)->first();
        
        if(isset($keyword->id)){
            // dd($keyword->keyword_records);
            return view('keyword_detail' , ['keyword' => $keyword]);
            
        }else{
            
            return back()->with('error' , 'Keyword not found.');
            
        }
    }

    public function emailList(Request $request)
    {
        if((isset($request->email) && !empty($request->email)) || (isset($request->title) && !empty($request->title))){
           
            $title = $request->input('title');
            $email = $request->input('email');

            $query = KeywordRecord::query();
            if(!empty($title)){
                Session::put('title', $title);
                $query->where('title', 'like', '%' . $title . '%');
            }
            if(!empty($email)){

                Session::put('email', $email);
                $query->orWhere('email', $email);
            }

            $email_list = $query->paginate(20);

        }else{

            if(session('title')){
                session()->forget('title');
            }
            if(session('email')){
                session()->forget('email');
            }
            $email_list = KeywordRecord::paginate(20);
        }

        return view('email_list' , ['email_list' => $email_list]);
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
        // dd($request);
        if($request->keyword == ''){
           
            $data = KeywordRecord::select('title' , 'email')->get();
        
        }else{

            $data = KeywordRecord::where('keyword_id' , $request->keyword)->select('title' , 'email')->get();

        }
        
        return (new FastExcel($data))->download('file.xlsx');

    }

    public function searchKeyword(Request $request)
    {
        $title = $request->input('title');
        $email = $request->input('email');

        $query = KeywordRecord::query();
        if(!empty($title)){
            Session::put('title', $title);
            $query->where('title', 'like', '%' . $title . '%');
        }
        if(!empty($email)){

            Session::put('email', $email);
            $query->orWhere('email', $email);
        }

        $keyword = $query->paginate(20);

    }
}
