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

Route::get('/', function () {
    return view('pages.home');
});

Route::get('about', function()
{
    return View::make('pages.about');
});

Route::get('profile', 'ProfileController@index');

Route::get('building_report/{building_id}', function()
{
    return View::make('pages.building_report');
});

Route::get('login', function()
{
    return View::make('pages.login');
});

Route::post('login', 'MyLoginController@authenticate');

Route::get('signup', function()
{
    return View::make('pages.signup');
});

Route::get('api/buildings', function (Request $request) {
	return Building::all();
});
