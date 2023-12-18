<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeywordController;
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

Route::match(['get' ,'post'] ,'/', [KeywordController::class , 'index'])->name('dashboard')->middleware('auth');
Route::post('/add/keyword', [KeywordController::class , 'addKeyword'])->name('add_keyword')->middleware('auth');
Route::get('/keyword/detail/{id}', [KeywordController::class , 'keywordDetail'])->name('keyword_detail')->middleware('auth');
Route::match(['get' ,'post']  , '/email/list', [KeywordController::class , 'emailList'])->name('email_list')->middleware('auth');
Route::get('/delete/keyword/{id}', [KeywordController::class , 'deleteKeyword'])->name('delete_keyword')->middleware('auth');
Route::post('/export', [KeywordController::class , 'export'])->name('export')->middleware('auth');
Route::post('/search/keyword', [KeywordController::class , 'searchKeyword'])->name('search_keyword')->middleware('auth');
Route::post('/import/csv', [KeywordController::class , 'ImportSentEmailCsv'])->name('import_csv')->middleware('auth');

Route::get('/clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
 
    return "Cleared!";
 
 });

require __DIR__.'/auth.php';


