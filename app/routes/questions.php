<?php
Route::get('question-explanation/{question_id}', 'QuestionExplanationController@show');
Route::get('api/question-explanation/{question_id}', 'QuestionExplanationController@getExplanation');
Route::get('api/is-using-screen-reader', 'QuestionExplanationController@isUsingScreenReader');
