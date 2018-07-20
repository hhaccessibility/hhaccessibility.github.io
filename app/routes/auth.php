<?php

Route::group(['prefix' => 'signin'], function () {
    Route::get('/', 'SignInController@showForm');
    Route::post('/', 'SignInController@authenticate');
});

Route::group(['prefix' => 'signup'], function () {
    Route::get('/', 'SignUpController@showForm');
    Route::post('/', 'SignUpController@createUser');
    Route::get('/confirmEmail/{user_email}/{email_verification_token}', 'SignUpController@confirmEmail');
});

Route::get('socialauth/auth/{provider}', 'SocialAuthController@getSocialLogin');
Route::get('socialauth/auth', 'SocialAuthController@getSocialLoginCallBack');

Route::get('fbauth/{auth?}', array('as'=>'facebookAuth', 'uses'=>'SocialAuthController@getFacebookLogin'));

Route::get('signout', 'SignInController@signout');
