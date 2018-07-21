<?php
Route::group(['prefix' => 'user/password-recovery'], function () {
    Route::get('/', 'PasswordRecoveryController@form');
    Route::post('/', 'PasswordRecoveryController@sendEmail');
    Route::get('/{user_email}/{password_recovery_token}', 'PasswordRecoveryController@passwordRecover');
    Route::post('/reset-password', 'PasswordRecoveryController@resetPassword');
});
Route::group(['prefix' => 'user/change-password'], function () {
    Route::get('/', 'ChangePasswordController@index');
    Route::post('/', 'ChangePasswordController@post');
});
