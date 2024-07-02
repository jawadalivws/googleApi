<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Keyword;
use App\Models\KeywordLocation;
use App\Models\KeywordRecord;
use Illuminate\Support\Facades\Http;
use Goutte;
use Rap2hpoutre\FastExcel\FastExcel;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class KeywordController extends Controller
{

    public function index(Request $request)
    {
        // dd($request->method());
        if($request->method() == 'POST'){

            $query = Keyword::query();

            session()->forget('searchCampaign');
            session()->forget('searchKeyword');
            session()->forget('createdFrom');
            session()->forget('createdTo');

            $query = $query->with('keyword_records');
            if(!empty($request->searchKeyword)){

                Session::put('searchKeyword',$request->searchKeyword);
                $query->where('name' , 'like' , '%'.$request->searchKeyword.'%');
            }
            if(!empty($request->createdFrom)){
                Session::put('createdFrom',$request->createdFrom);
                $query->where('created_at' , '>=' , $request->createdFrom);
            }
            if(!empty($request->createdTo)){
                Session::put('createdTo',$request->createdTo);
                $query->where('created_at' , '<=' , $request->createdTo);
            }
            if(!empty($request->searchCampaign)){
                Session::put('searchCampaign',$request->searchCampaign);
                $query->where('compain_id' , 'like' , '%'.$request->searchCampaign.'%');
            }

            $keywords = $query->latest()->paginate(10);

        }else{

            session()->forget('searchKeyword');
            session()->forget('searchCampaign');
            session()->forget('createdFrom');
            session()->forget('createdTo');

            $keywords = Keyword::with('keyword_records')->latest()->paginate(20);
        }

        // $scanned_ids = KeywordRecord::pluck('keyword_id')->groupBy('keyword_id')->first();
        $total_email = KeywordRecord::count();
        $countries = Country::get();

        $email_sent = KeywordRecord::where('email_sent' , 1)->count();
        $pending_email = $total_email - $email_sent;

        $data['keywords'] = $keywords;
        $data['total_email'] = $total_email;
        $data['email_sent'] = $email_sent;
        $data['pending_email'] = $pending_email;
        $data['countries'] = $countries;
        

        // if($scanned_ids != null){

        //     $records = Keyword::whereIn('id', $scanned_ids)->get();

        // }
        return view('dashboard', $data);
    }
    public function addKeyword(Request $request)
    {
  
        // dd($request->all());
        DB::beginTransaction();
        try {
            
            $validator = Validator::make($request->all() , [
                'keyword' => 'required|unique:keywords,name',
                'campaign_id' => 'required|numeric',
                "country_array" => "required|array",
                "country_array.*" => "required"
            ]);
    
            if($validator->fails()){
                return response()->json(['success' => false , 'messages' => $validator->getMessageBag()] , 422);
            }
            
            $insert = Keyword::create([
                'name' => $request->keyword,
                'compain_id' => $request->campaign_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    // dd($request->country_array);
            foreach($request->country_array as $country){
                if(isset($country['states']) && count($country['states']) > 0){
                    foreach($country['states'] as $state){
                        if(isset($state['cities']) && count($state['cities']) > 0){
                            foreach($state['cities'] as $city){
                                KeywordLocation::create([
                                    'keyword_id' => $insert->id,
                                    'city_id' => $city['id'],
                                ]);
                            }
                        }else{
                            KeywordLocation::create([
                                'keyword_id' => $insert->id,
                                'state_id' => $state['id'],
                            ]); 
                        }
                    }
                }else{
    
                    KeywordLocation::create([
                        'keyword_id' => $insert->id,
                        'country_id' => $country['id'],
                    ]); 
                }
            }
    
            DB::commit();
            return response()->json(['success' => true  , 'message' => 'Keyword Added successfully.'] ,200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['success' => false  , 'message' => $th->getMessage()] ,200);
        }
        
        // for ajax response
        // if($insert){
        //     $data['success'] = true;
        //     $data['message'] = 'Word Added Successfully.';
        //     return response()->json($data);
        // }
    }
    public function updateKeyword(Request $request)
    {
  
        $request->validate([
            'keyword' => 'required',
            'campaign_id' => 'required|numeric',
        ]);
        
        $update = Keyword::where('id' , $request->id)->update([
            'name' => $request->keyword,
            'compain_id' => $request->campaign_id,
            'updated_at' => Carbon::now(),
        ]);

        return back()->with('success' , 'keyord Updated Successfully.');
        // for ajax response
        // if($insert){
        //     $data['success'] = true;
        //     $data['message'] = 'Word Added Successfully.';
        //     return response()->json($data);
        // }
    }

    public function deleteKeyword(Request $request)
    {
        DB::beginTransaction();
        $id = $request->id;
        $exist = Keyword::where('id' , $id)->first();

        if($exist)
        {
            $delete = Keyword::where('id' , $id)->delete();
            KeywordLocation::where('keyword_id' , $id)->delete();

            $data['success'] = true;
            $data['message'] = 'Keyword deleted Successfully.';
            DB::commit();
            return response()->json($data);

        }

        DB::rollBack();
        return response()->json();
    }
    
    public function keywordDetail($id)
    {
        $keyword = Keyword::where('id' , $id)->first();
        $segment_one = request()->segment(1);
        $segment_two =  request()->segment(2);

        $uri = $segment_one.'/'.$segment_two;
        if(isset($keyword->id)){
            // dd($keyword->keyword_records);
            return view('keyword_detail' , ['keyword' => $keyword , 'uri' => $uri]);
            
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

            $email_list = $query->latest()->paginate(50);

        }else{

            session()->forget('title');
            session()->forget('email');
            session()->forget('search_keyword');
            session()->forget('createdFrom');
            session()->forget('createdTo');
            
            $email_list = KeywordRecord::latest()->paginate(50);
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
           
            $data = KeywordRecord::select('email')->where('email_sent' , false)->get();
        
        }else{

            $data = KeywordRecord::where('keyword_id' , $request->keyword)->where('email_sent' , false)->select('email')->get();

        }
        
        $extraFields = collect([
            'first_name' => '',
            'last_name' => '',
            'company' => '',
            'Position' => '',
            'Connection On' => '',
            'City' => '',
            'State' => '',
            'Phone' => '',
            'Custom Field 1' => '',
            'Custom Field 2' => '',
            'Custom Field 3' => '',
            // Add as many extra fields as needed
        ]);

        // $dataWithExtraFields = $data->map(function ($item) use ($extraFields) {
        //     return $item->merge($extraFields);
        // });

        $dataWithExtraFields = $data->map(function ($item) use ($extraFields) {
            return array_merge($item->toArray(), $extraFields->toArray());
        });

        $finalData = $dataWithExtraFields->map(function ($item) {
            return [
                'First Name' => $item['first_name'],
                'Last Name' => $item['last_name'],
                'Email' => $item['email'],
                'Company' => $item['company'],
                'Position' => $item['Position'],
                'Connection On' => $item['Connection On'],
                'City' => $item['City'],
                'State' => $item['State'],
                'Phone' => $item['Phone'],
                'Custom Field 1' => $item['Custom Field 1'],
                'Custom Field 2' => $item['Custom Field 2'],
                'Custom Field 3' => $item['Custom Field 3'],
            ];
        });
        

        return (new FastExcel($finalData))->download('email.xlsx');

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

            // dd($line['Email']);
            $update = KeywordRecord::where('email', $line['Email'])->update(['email_sent' => true]);

        });

        return back()->with('success' , 'File Imported Successfully');
        
    }

    public function putDate(Request $request)
    {               
        $stringDate = $request->date;
        $id = $request->id;
        $date = Carbon::parse($stringDate);
        // dump($id);
        // dd($date);
        $update = KeywordRecord::where('keyword_id' , $id)->update([
            'created_at' => $date,
        ]);
        if($update){
            dd('done');
        }
        dd('error');
    }

    public function updateEmailRecord(Request $request)
    {
        $email = $request->email?$request->email:null;

        if($email != null){

            $email_exist = KeywordRecord::where('email' , $email)->first();

            if(isset($email_exist->id)){

                $update = new KeywordRecord;
                $update = $update->where('email' , $email)->first();
                $update->email_sent = true;
                $update->save();
                if(isset($update->id)){

                    return response(['success' => true , 'message' => 'Email Record Updated Successfully.' , 'status' => 200]);
                }else{

                    return response(['success' => false , 'message' => 'Something went wrong.' ,  'status' => 201]);
                }
            }else{

                return response(['success' => false , 'message' => 'Email Not Found' ,  'status' => 201]);
            }
        }else{
            return response(['success' => false , 'message' => 'Email cannot be empty' ,  'status' => 201]);
        }
    }

}