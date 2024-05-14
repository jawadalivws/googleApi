<?php

use App\Http\Controllers\CityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StateController;
use GuzzleHttp\Middleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');
Route::group(['middleware' => 'auth'] , function(){

    Route::match(['get' ,'post'] ,'/', [KeywordController::class , 'index'])->name('dashboard');
    Route::post('/add/keyword', [KeywordController::class , 'addKeyword'])->name('add_keyword');
    Route::post('/update/keyword', [KeywordController::class , 'updateKeyword'])->name('update_keyword');
    Route::get('/keyword/detail/{id}', [KeywordController::class , 'keywordDetail'])->name('keyword_detail');
    Route::match(['get' ,'post']  , '/email/list', [KeywordController::class , 'emailList'])->name('email_list');
    Route::get('/delete/keyword/{id}', [KeywordController::class , 'deleteKeyword'])->name('delete_keyword');
    Route::match(['get' ,'post']  ,'/export', [KeywordController::class , 'export'])->name('export');
    Route::match(['get' ,'post'] , '/search/keyword', [KeywordController::class , 'searchKeyword'])->name('search_keyword');
    Route::match(['get' ,'post'] , '/putdate', [KeywordController::class , 'putDate'])->name('putdate');
    Route::post('/import/csv', [KeywordController::class , 'ImportSentEmailCsv'])->name('import_csv');

    Route::match(['get' ,'post'] , '/setting', [SettingController::class , 'index'])->name('setting');
    Route::match(['get' ,'post'] , 'add/campaign_id', [SettingController::class , 'store'])->name('add_campaign_id');
    Route::match(['get' ,'post'] , '/campaign_id_update', [SettingController::class , 'update'])->name('campaign_id_update');

    Route::match(['get' ,'post'] , '/load_states', [StateController::class , 'loadStates'])->name('load_states');
    Route::match(['get' ,'post'] , '/load_cities', [CityController::class , 'loadCities'])->name('load_cities');

});

Route::get('/clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
 
    return "Cleared!";
 
 });

require __DIR__.'/auth.php';


