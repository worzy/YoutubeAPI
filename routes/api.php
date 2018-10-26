<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// End point to trigger storing of videos
Route::get('videos/store', 'VideoController@storeFilter');

// End point to search videos
Route::get('videos/search', 'VideoController@search');

// End point to fetch all videos
Route::get('videos', 'VideoController@index');

// End point to fetch all videos
Route::get('videos/{video}', 'VideoController@show');

// End point to delete a video
Route::delete('videos/{video}', 'VideoController@destroy');

