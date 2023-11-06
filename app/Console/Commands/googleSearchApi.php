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

                $api_key = env('GOOGLE_SEARCH_API_KEY');
                $search_id = env('GOOGLE_SEARCH_ENGINE_ID');
                
                try{

                    // if($word->response != null){

                    //     $response = $word->response;
                    //     $data = json_decode($response);

                    // }else{
                        $response = HTTP::get('https://www.googleapis.com/customsearch/v1' , [
                            'key' => "AIzaSyD_JX05N8iglGiYSb5SEwnUzNFhnowFeQ4",
                            'cx' => "e04af8e8fd9264b82",
                            'q' => $word->name,
                        ]);
                
                        // $data = json_encode($response->getBody());
    
                        // Keyword::where('id' , $word->id)->update([
                        //     'response' => $response,
                        // ]);
                        
                        $data = json_decode($response->getBody());
                    // }


                    // dd($data);


                    $allEmails = array();
                    if(isset($data->items)){
                        $results = $data->items;
                        foreach($results as $item){
                            // dump($item->link);
                            
                            $email = '';
                            $contact = '';
                            
                            $checkUrl = @file_get_contents($item->link);

                            if($checkUrl === false){
                                // dump('Problem in url');
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
    
                                        $dataToInsert[] = [
                                            'keyword_id' => $word->id,
                                            'email' => $email,
                                            'title' => $item->title,
                                            'url' => $contact,
                                        ];
                    
                                        if (!empty($dataToInsert)) {
                                            KeywordRecord::insert($dataToInsert);
                                        }
    
                                    }
                                }

                            }
                        }
                    }
                }catch(Throwable $e){
                    dd($e->getMessage());
                }
            }  
        }
        
        dd("jawad");
    }
}
