<?php

Route::get('location/search', 'LocationSearchController@search');
Route::get('location/management/nearby/{longitude}/{latitude}', 'LocationManagementController@getLocationsNear');
Route::get('location/management/delete/{location_id}', 'LocationManagementController@deleteMyLocation');
Route::get('location/management/modify/{location_id}', 'LocationManagementController@show');
Route::get('location/management/add', 'LocationManagementController@addNewLocation');
Route::post('location/management/add', 'LocationManagementController@addNewLocationSave');
Route::get('location/management/my-locations', 'LocationManagementController@showCurrentUserLocations');
Route::get(
    'location-suggestions-for-name/{location_name}',
    'LocationManagementController@getLocationSuggestionsForLocationName'
);
Route::get('location/report/{location_id}', 'LocationReportController@show');
Route::get('location/report/{location_id}/{rating_system}', 'LocationReportController@show');
Route::get('location/event/{location_event_id}', 'LocationEventController@show');
Route::get('location-comments/{location_id}', 'LocationReportController@showComments');
Route::get('location-map/{location_id}', 'LocationReportController@showMap');
Route::get('location-map/{location_id}/{rating_system}', 'LocationReportController@showMap');
Route::get('location-comprehensive-ratings/{location_id}', 'LocationReportController@showComprehensiveRatings');
Route::get(
    'location-comprehensive-ratings/{location_id}/{rating_system}',
    'LocationReportController@showComprehensiveRatings'
);
Route::get('location-reporting/{location_id}/{question_category_id}', 'LocationReportController@show2');
Route::post('api/set-search-radius', 'LocationSearchController@setSearchRadius');
Route::post('api/add-suggestion', 'SuggestionController@addSuggestion');
Route::get('suggestion-list/{location_id}', 'SuggestionController@showSuggestionList');
Route::get('suggestion-detail/{suggestion_id}', 'SuggestionController@showSuggestionDetail');
Route::get('location/management/edit/{location_id}', 'LocationManagementController@editLocation');
Route::post('location/management/edit', 'LocationManagementController@editLocationSave');
