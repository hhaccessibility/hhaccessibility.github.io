<?php

Route::get('manifest.json', 'PWAController@manifest');
Route::get('api/location/nearby/{longitude}/{latitude}', 'PWAController@getNearbyLocationToRate');
