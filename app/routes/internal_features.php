<?php
Route::get('user-report/{user_id}', 'InternalFeaturesController@showUserReport');
Route::post('api/user/delete', 'InternalFeaturesController@deleteUser');
Route::get('users', 'InternalFeaturesController@showUsers');
Route::get('location-groups', 'InternalFeaturesController@showLocationGroupsReport');
Route::get('location-tagging', 'LocationTaggingController@search');
Route::post('location/tag', 'LocationTaggingController@addTag');
Route::delete('location/tag', 'LocationTaggingController@removeTag');
Route::get('dashboard', 'InternalFeaturesController@showDashboard');
Route::get('map-location-visualizer', 'InternalFeaturesController@showMapVisualizer');
