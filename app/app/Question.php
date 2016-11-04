<?php

namespace App;
use Eloquent;

class Question extends Eloquent
{
    protected $fillable = [
        'question_html',
    ];
	
	protected $table = 'question';	
}
