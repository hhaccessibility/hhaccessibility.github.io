<?php

Route::group(['prefix' => 'profile'], function () {
    Route::get('/', 'ProfileController@index');
    Route::post('/', 'ProfileController@save');
});
Route::group(['prefix' => 'profile-photo-upload'], function () {
    Route::get('/', 'ProfilePhotoUploadController@index');
    Route::post('/', 'ProfilePhotoUploadController@post');
});

Route::get('profile-photo', 'ProfilePhotoUploadController@photo');
Route::post('profile-photo-rotate', 'ProfilePhotoUploadController@rotate');
Route::get('profile-photo-delete', 'ProfilePhotoUploadController@delete');
