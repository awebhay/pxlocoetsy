<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/getLinks',"DemoController@getLinks");
Route::post('/getLinksInShop',"DemoController@getLinksInShop");

Route::get('/download',"DemoController@download");
Route::post('/truncate',"DemoController@truncateDB");

Route::get('done', function () {
    return redirect('dashboard');
})->name('download.done');
