<?php

use App\Location;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'HomeController@index');

Route::get('our-vision', function()
{
    return View::make('pages.our_vision');
});

Route::get('profile', 'ProfileController@index');
Route::get('location-search', 'LocationSearchController@index');
Route::get('location-report/{location_id}', 'LocationReportController@show');
Route::get('search-by-tag/{location_tag_id}', 'LocationSearchController@by_tag');
Route::get('search-by-keywords', 'LocationSearchController@byKeywords');

Route::get('login', function()
{
    return View::make('pages.login');
});

Route::post('login', 'MyLoginController@authenticate');

Route::get('fbauth/{auth?}', array('as'=>'facebookAuth', 'uses'=>'SocialAuthController@getFacebookLogin'));

Route::get('signup', function()
{
    return View::make('pages.signup');
});

Route::get('api/locations', function (Request $request) {
	return Location::all();
});
