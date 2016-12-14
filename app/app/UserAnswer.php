<?php

namespace App;
use Eloquent;

class UserAnswer extends Eloquent
{
    protected $fillable = [
        'answered_by_user_id', 'question_id', 'location_id', 'answer_value', 
    ];
	public $timestamps = false;
	
	protected $table = 'user_answer';
}
