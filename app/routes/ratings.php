<?php
Route::get('location-rating/{location_id}', 'LocationRatingController@show');
Route::get('location-rating/{location_id}/{question_category_id}', 'LocationRatingController@show');
Route::post('location-rating-submit', 'LocationRatingController@submit');
Route::put('location-rating/answer', 'LocationRatingController@setAnswer');
Route::delete('location-rating/answer', 'LocationRatingController@removeAnswer');
Route::put('location-rating/comment', 'LocationRatingController@setComment');
Route::post('location-rating-commit', 'LocationRatingController@commitReview');
Route::get('reviewed-locations', 'LocationRatingController@reviewedLocations');
