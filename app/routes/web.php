<?php

Route::get('/', 'HomeController@index');
Route::post('save-user-location', 'HomeController@saveUserLocation');
Route::get('terms-of-use', function() { return View::make('pages.terms_of_use'); });
Route::get('privacy-policy', function() { return View::make('pages.privacy_policy'); });
Route::get('faq', 'FaqController@index');
Route::get('duplicate-location-finder', 'DuplicateLocationFinderController@showDuplicateLocationFinder');
Route::post('time-zone', 'TimeZoneController@setTimeZone');

/* Authentication Routes */
require "auth.php";

/* Password Recovery Routes */
require "password_recovery.php";

/* Contact Routes */
require  "contact.php";

/* Profile Routes */
require "profile.php";

/* Location */
require "location.php";

/* Location Ratings */
require "ratings.php";

/* Internal Features Routes */
require "internal_features.php";

/* Questions Routes */
require "questions.php";
