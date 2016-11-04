<?php

namespace App;
use Eloquent;

class QuestionCategory extends Eloquent
{
    protected $fillable = [
        'name',
    ];
	
	protected $table = 'question_category';	
}
