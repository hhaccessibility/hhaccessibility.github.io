<?php

namespace App;

class QuestionCategory extends Eloquent
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
	
	protected $table = 'question_category';	
}
