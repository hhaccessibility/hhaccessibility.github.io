<?php

Route::group(['prefix' => 'contact'], function () {
    Route::get('/', 'ContactController@index');
    Route::post('/', 'ContactController@sendMessage');
});
