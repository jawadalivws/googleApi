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


Route::get('/', [KeywordController::class , 'index'])->name('dashboard')->middleware('auth');
Route::post('/add/keyword', [KeywordController::class , 'addKeyword'])->name('add_keyword');
Route::get('/keyword/detail/{id}', [KeywordController::class , 'keywordDetail'])->name('keyword_detail');
// Route::get('/delete/keyword', [KeywordController::class , 'index'])->name('dashboard');
Route::post('/export', [KeywordController::class , 'export'])->name('export');
require __DIR__.'/auth.php';
