<?php
Route::get('user-report/{user_id}', 'InternalFeaturesController@showUserReport');
Route::post('api/user/delete', 'InternalFeaturesController@deleteUser');
Route::get('users', 'InternalFeaturesController@showUsers');
Route::get('location-groups', 'InternalFeaturesController@showLocationGroupsReport');
Route::get('dashboard', 'InternalFeaturesController@showDashboard');
Route::get('map-location-visualizer', 'InternalFeaturesController@showMapVisualizer');
