<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Keyword;
use App\Models\KeywordLocation;
use App\Models\KeywordRecord;
use Illuminate\Support\Facades\Http;
use Goutte;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\URL;
require 'vendor/autoload.php'; // Load the Composer autoloader
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Support\Facades\Cookie;

class googleSearchApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'googleSearchApi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $scannedIds = KeywordRecord::groupBy('keyword_id')->pluck('keyword_id');
        $words = Keyword::whereHas('keyword_locations' , function($query){
            $query->where('scanned' , false);
        })->with('keyword_locations')->get();

        // $campaign_id = Setting::first();
        // $campaign_id = $campaign_id->campaign_id;

        if(count($words) > 0){
            foreach($words as $word){
                foreach($word->keyword_locations as $record){
                    if($record->city){

                        $name = $word->name.' '.$record->city->name;
                    }
                    if($record->state){
                        $name = $word->name.' '.$record->state->name;
                    }
                    if($record->country){
                        $name = $word->name.' '.$record->country->name;
                    }


                    $campaign_id = $word->compain_id;
                    $api_key = env('GOOGLE_SEARCH_API_KEY');
                    $search_id = env('GOOGLE_SEARCH_ENGINE_ID');
                    $results_per_page = 10;
                    $page = 1;
                    $dataToInsert = [];
                    $email_array = [];
                    // AIzaSyB0BGGeRNKp9Y_Sb8kLy4DCxOELJ3tQdro
                    // AIzaSyBLNws_02Wl2y53UCoOv3KKu0RVDalh4zs
                    // AIzaSyD1NxONlC1SzOGW5C1icrGYpjJLKCP6CK4
                    // AIzaSyAnwohGSvvJ_O5sRofH2ZvxKaSsfJr2pN4
                    do{
    
                        try{
                            // if($word->response != null){
        
                            //     $response = $word->response;
                            //     $data = json_decode($response);
        
                            // }else{
                                
    
                                $response = HTTP::get('https://www.googleapis.com/customsearch/v1' , [
                                    'key' => "AIzaSyB0BGGeRNKp9Y_Sb8kLy4DCxOELJ3tQdro",
                                    'cx' => "432d043d77144425f",
                                    'q' => $word->name,
                                    'start' => ($page - 1) * $results_per_page + 1, // Calculate the starting index for the current page
                                    'num' => $results_per_page,
                                ]);
                                
                                // dump($word);
                                $data = json_encode($response->getBody());
        
                                // Keyword::where('id' , $word->id)->update([
                                //     'response' => $response,
                                // ]);
                                
                                $data = json_decode($response->getBody());
                            // }
        
                            // \Log::info($data);
        
                            // dd($data);
        
                            if (Cookie::get('jawad') !== null){
                                dump($name);
                            }
        
                            $allEmails = array();
                            $flag = false;
                            if(isset($data->items)){
                                $results = $data->items;
                                foreach($results as $item){
                                    // dump($item->link);
                                  try{
    
                                    $email = '';
                                    $contact = '';
                                    
                                    $checkUrl = @file_get_contents($item->link);
        
                                    if($checkUrl === false || $flag == true){
                                        // dump('Problem in url');
                                        $flag = false;
                                    }else{
        
                                        $crawler = Goutte::request('GET', $item->link); 
                                        
                                        if($crawler->count() > 0){
        
                                            $page_content = $crawler->filter('html')->html();
            
                                            $pattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/';
                            
                                            if(preg_match_all($pattern , $page_content , $matches)){
            
                                                $emails = $matches[0];
        
                                                if (Str::contains($emails[0], ['.png', '.jpeg', '.jpg','.gif', '.pdf' , '.tiff','.psd' , '.eps', '.ai', '.indd','.raw' ,'.txt','.ppt'])) {
                                                }else{
                                                    $exists = KeywordRecord::where('email' , $emails[0])->first();
                                                    // dump($emails[0]);
                                                    if(isset($exists->id)){
        
                                                    }else{
                                                        $email = $emails[0];
                                                    }
                                                }
                                            }
            
                                            // $crawler = new Crawler($page_content);
            
                                            // $contactAnchors = $crawler->filter('a:contains("Contact")');
            
                                            // if(!empty($contactAnchors)){
                                                                                
                                            //     $contactUrls = $contactAnchors->each(function (Crawler $node, $i) {
                                            //         return $node->attr('href');
                                            //     });
                                                
                                            //     $itemLink = $item->link;
                
                                            //     $baseUrl = parse_url($itemLink, PHP_URL_SCHEME) . '://' . parse_url($itemLink, PHP_URL_HOST);
                
                                            //     if (!empty($contactUrls)) {
                                                    
                                            //         $relative_url = parse_url($contactUrls[0], PHP_URL_PATH);
                
                                            //         $contact = $baseUrl. $relative_url;
            
                                            //     }
                                            // }
                                            if($email && !in_array($email , $email_array)){
                                                dump($email);
                                                $email_array[] = $email;
                                                $dataToInsert[] = [
                                                    'keyword_id' => $word->id,
                                                    'keyword_location' => $record->id,
                                                    'email' => $email,
                                                    'title' => $item->title,
                                                    'url' => $contact,
                                                    'created_at' => Carbon::now(),
                                                    'updated_at' => Carbon::now(),
                                                ];
                                                
            
                                            }
                                        }
        
                                    }
    
                                  }catch(\Throwable $e){
                                    // dump("Error: " . $e->getMessage());
                                    \Log::info("Error ". $e->getMessage());
                                    $flag = true;
                                }
              
                                }
                            }
    
                            $page++;
    
                        }catch(\Throwable $e){
                            echo "Error: " . $e->getMessage();
                            break;
                        }
    
                    }while($page <= PHP_INT_MAX && isset($data->queries->nextPage));

                    if (!empty($dataToInsert)) {
                        \Log::info("data insert");
                        KeywordRecord::insert($dataToInsert);
                        dump('inserted');
                        foreach($dataToInsert as $data){
                            // dump($data['email']);
                            $curl = curl_init();
    
                            curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://email.updatemedaily.com/campaigns/add_campaign_email',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => array('campaign_id' => $campaign_id,'contact_email' => $data['email']),
                            CURLOPT_HTTPHEADER => array(
                                'Cookie: ci_session=p24kmm1qgsn7dnnlilifndr6a7s3g7kc'
                            ),
                            ));
                    
                            $response = curl_exec($curl);
                    
                            curl_close($curl);
                            echo $response;
                        }
                    }
                    KeywordLocation::where('id' , $record->id)->update(['scanned' => true]);

                } // locations loop end

            }  // keyword loop end
        }
        
        // dd("jawad");
    }
}
