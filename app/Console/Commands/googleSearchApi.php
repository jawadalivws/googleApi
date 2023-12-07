<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Keyword;
use App\Models\KeywordRecord;
use Illuminate\Support\Facades\Http;
use Goutte;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\URL;
require 'vendor/autoload.php'; // Load the Composer autoloader
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $words = Keyword::whereNotIn('id' , $scannedIds)->get();
        
        $record = array();
        if(count($words) > 0){
            foreach($words as $word){
                \Log::info("foreach");
                $api_key = env('GOOGLE_SEARCH_API_KEY');
                $search_id = env('GOOGLE_SEARCH_ENGINE_ID');
                $results_per_page = 10;
                $page = 1;
                
                do{

                    try{

                        // if($word->response != null){
    
                        //     $response = $word->response;
                        //     $data = json_decode($response);
    
                        // }else{
                            $response = HTTP::get('https://www.googleapis.com/customsearch/v1' , [
                                'key' => "AIzaSyB0BGGeRNKp9Y_Sb8kLy4DCxOELJ3tQdro",
                                'cx' => "41f26495cdd8c4ed4",
                                'q' => $word->name,
                                'start' => ($page - 1) * $results_per_page + 1, // Calculate the starting index for the current page
                                'num' => $results_per_page,
                            ]);
                            // dump($word);
                            $data = json_encode($response->getBody());
    
                            \Log::info("data :" .$data);
                            Keyword::where('id' , $word->id)->update([
                                'response' => $response,
                            ]);
                            
                            $data = json_decode($response->getBody());
                        // }
    
                        // \Log::info($data);
    
                        // dd($data);
    
    
                        $allEmails = array();
                        $flag = false;
                        if(isset($data->items)){
                            \Log::info("data");
                            $results = $data->items;
                            foreach($results as $item){
                                \Log::info("item");
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
                                                dump($emails[0]);
                                                if(isset($exists->id)){
    
                                                }else{
                                                    $email = $emails[0];
                                                }
                                            }
                                        }
                        
        
                                        $crawler = new Crawler($page_content);
        
                                        $contactAnchors = $crawler->filter('a:contains("Contact")');
        
                                        if(!empty($contactAnchors)){
                                                                            
                                        $contactUrls = $contactAnchors->each(function (Crawler $node, $i) {
                                            return $node->attr('href');
                                        });
                                        
                                        $itemLink = $item->link;
        
                                        $baseUrl = parse_url($itemLink, PHP_URL_SCHEME) . '://' . parse_url($itemLink, PHP_URL_HOST);
        
                                        if (!empty($contactUrls)) {
                                            
                                            $relative_url = parse_url($contactUrls[0], PHP_URL_PATH);
        
                                            $contact = $baseUrl. $relative_url;
    
                                        }
                                        }
                                        $dataToInsert = [];
        
                                        if($email != ''){
                                            \Log::info("email found");
                                            $dataToInsert[] = [
                                                'keyword_id' => $word->id,
                                                'email' => $email,
                                                'title' => $item->title,
                                                'url' => $contact,
                                            ];
                    
                                            if (!empty($dataToInsert)) {
                                                \Log::info("data insert");
                                                KeywordRecord::insert($dataToInsert);
                                            }
        
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
            }  
        }
        
        dd("jawad");
    }
}
