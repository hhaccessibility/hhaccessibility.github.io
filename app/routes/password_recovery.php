<?php
Route::get('password-recovery', 'PasswordRecoveryController@form');
Route::get('password-recovery/{user_email}/{password_recovery_token}', 'PasswordRecoveryController@passwordRecover');
Route::post('password-recovery', 'PasswordRecoveryController@sendEmail');
Route::post('password-recovery/reset-password', 'PasswordRecoveryController@resetPassword');
Route::get('change-password', 'ChangePasswordController@index');
Route::post('change-password', 'ChangePasswordController@post');
