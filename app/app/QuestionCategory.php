<?php

namespace App;
use Eloquent;

class QuestionCategory extends Eloquent
{
    protected $fillable = [
        'id', 'name',
    ];
	
	protected $table = 'question_category';
	
    public function questions()
    {
        return $this->hasMany('App\Question');
    }	
	
}
