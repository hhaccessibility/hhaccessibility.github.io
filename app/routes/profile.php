<?php

Route::group(['prefix' => 'profile'], function () {
    Route::get('/', 'ProfileController@index');
    Route::post('/', 'ProfileController@save');
    Route::get('/names', 'UserNamesController@index');
    Route::post('/names', 'UserNamesController@save');
});
Route::group(['prefix' => 'profile-photo-upload'], function () {
    Route::get('/', 'ProfilePhotoController@index');
    Route::post('/', 'ProfilePhotoController@post');
});

Route::get('profile-photo', 'ProfilePhotoController@photo');
Route::post('profile-photo/rotate', 'ProfilePhotoController@rotate');
Route::get('profile-photo/delete', 'ProfilePhotoController@delete');
