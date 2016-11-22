<?php

use App\Building;

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

Route::get('about', function()
{
    return View::make('pages.about');
});

Route::get('profile', 'ProfileController@index');

Route::get('building-report/{building_id}', function()
{
    return View::make('pages.building_report');
});

Route::get('search-by-tag/{building_tag_id}', 'BuildingSearchController@by_tag');

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

Route::get('api/buildings', function (Request $request) {
	return Building::all();
});
