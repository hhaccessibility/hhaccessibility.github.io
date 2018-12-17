<?php

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

Route::post('populate-ratings-cache', 'LocationRatingCacheController@populateRatingsCache');
Route::post(
    'populate-root-group-ratings-cache',
    'LocationRatingCacheController@populateUngroupedLocationGroupRatingsCache'
);
Route::post('populate-group-ratings-cache', 'LocationRatingCacheController@populateLocationGroupRatingsCache');
Route::get('question-explanation/{question_id}', 'QuestionExplanationController@getExplanation');
Route::get('regions', 'HomeAddressController@getRegions');
Route::get('locations', 'LocationSearchController@all');