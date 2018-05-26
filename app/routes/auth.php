<?php
Route::get('signin', 'SignInController@showForm');
Route::post('signin', 'SignInController@authenticate');
Route::get('signout', 'SignInController@signout');

Route::get('fbauth/{auth?}', array('as'=>'facebookAuth', 'uses'=>'SocialAuthController@getFacebookLogin'));

Route::get('socialauth/auth/{provider}', 'SocialAuthController@getSocialLogin');
Route::get('socialauth/auth', 'SocialAuthController@getSocialLoginCallBack');

Route::get('signup', 'SignUpController@showForm');
Route::post('signup', 'SignUpController@createUser');
Route::get('signup/confirmEmail/{user_email}/{email_verification_token}', 'SignUpController@confirmEmail');
