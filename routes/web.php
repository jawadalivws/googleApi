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

Route::get('/', [KeywordController::class , 'index'])->name('view_keyword');

Route::post('/add/keyword' , [KeywordController::class , 'addKeyword'])->name('add_keyword');
Route::get('/delete/keyword/{id}' , [KeywordController::class , 'deleteKeyword'])->name('delete_keyword');
Route::get('/keyword/detail/{id}' , [KeywordController::class , 'keywordDetail'])->name('keyword_detail');
Route::get('/delete/email/{id}' , [KeywordController::class , 'deleteEmail'])->name('delete_email');
Route::post('/export' , [KeywordController::class , 'export'])->name('export');
