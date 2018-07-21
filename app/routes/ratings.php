<?php

Route::group(['prefix' => 'location/rating'], function () {
    Route::get('/{location_id}', 'LocationRatingController@show');
    Route::get('/{location_id}/{question_category_id}', 'LocationRatingController@show');
    Route::put('/answer', 'LocationRatingController@setAnswer');
    Route::delete('/answer', 'LocationRatingController@removeAnswer');
    Route::put('/comment', 'LocationRatingController@setComment');
});

Route::post('location-rating-submit', 'LocationRatingController@submit');
Route::post('location-rating-commit', 'LocationRatingController@commitReview');
Route::get('reviewed-locations', 'LocationRatingController@reviewedLocations');
