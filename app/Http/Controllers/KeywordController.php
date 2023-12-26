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
use Carbon\Carbon;

class KeywordController extends Controller
{

    public function index(Request $request)
    {
        // dd($request);
        if(isset($request->searchKeyword) || isset($request->createdFrom) || isset($request->createdTo)){

            $query = Keyword::query();
            if(!empty($request->searchKeyword)){

                Session::put('searchKeyword',$request->searchKeyword);
                $query->where('name' , 'like' , '%'.$request->searchKeyword.'%');
            }else{
                session()->forget('searchKeyword');
            }
            if(!empty($request->createdFrom)){
                Session::put('createdFrom',$request->createdFrom);
                $query->where('created_at' , '>=' , $request->createdFrom);
            }else{
                session()->forget('createdFrom');
            }
            if(!empty($request->createdTo)){
                Session::put('createdTo',$request->createdTo);
                $query->where('created_at' , '<=' , $request->createdTo);
            }else{
                session()->forget('createdTo');
            }

            $keywords = $query->latest()->paginate(10);

        }else{

            session()->forget('searchKeyword');
            session()->forget('createdFrom');
            session()->forget('createdTo');

            $keywords = Keyword::latest()->paginate(10);
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
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
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
        if((isset($request->email) || isset($request->title) || isset($request->search_keyword)  || isset($request->createdFrom) || isset($request->createdTo))){ 

            $query = KeywordRecord::query();

            if(!empty($request->title)){

                Session::put('title', $request->title);
                $query->where('title', 'like', '%' . $request->title . '%');

            }else{
                session()->forget('title');
            }

            if(!empty($request->search_keyword)){

                Session::put('search_keyword', $request->search_keyword);
                $query->where('keyword_id', $request->search_keyword);

            }else{
                session()->forget('search_keyword');
            }

            if(!empty($request->email)){

                Session::put('email', $request->email);
                $query->where('email', 'like', '%' . $request->email . '%');

            }else{
                session()->forget('email');
            }
            
            if(!empty($request->createdFrom)){

                Session::put('createdFrom',$request->createdFrom);
                $query->where('created_at' , '>=' , $request->createdFrom);
            }else{
                session()->forget('createdFrom');
            }
            if(!empty($request->createdTo)){

                Session::put('createdTo',$request->createdTo);
                $query->where('created_at' , '<=' , $request->createdTo);
            }else{
                session()->forget('createdTo');
            }

            $email_list = $query->paginate(50);

        }else{

            session()->forget('title');
            session()->forget('email');
            session()->forget('search_keyword');
            session()->forget('createdFrom');
            session()->forget('createdTo');
            
            $email_list = KeywordRecord::paginate(50);
        }

        $keywords = Keyword::all();
        return view('email_list' , ['email_list' => $email_list , 'keywords' => $keywords]);
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
        if($request->keyword == '' || $request->has('keyword')){
           
            $data = KeywordRecord::select('title' , 'email')->where('email_sent' , false)->get();
        
        }else{

            $data = KeywordRecord::where('keyword_id' , $request->keyword)->where('email_sent' , false)->select('title' , 'email')->get();

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

    public function importSentEmailCsv(Request $request)
    {

        if (!$request->hasFile('file')) {
            throw new \Exception('File not found in the request.');
        }

        // Retrieve the file from the request
        $file = $request->file('file');


        // dump($file->getPathname());
        (new FastExcel)->import($file, function($line){

            // dump($line['email']);
            $update = KeywordRecord::where('email', $line['email'])->update(['email_sent' => true]);

        });

        return back()->with('success' , 'File Imported Successfully');
        
    }
}