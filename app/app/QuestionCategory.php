<?php

namespace App;
use Eloquent;

class QuestionCategory extends Eloquent
{
    protected $fillable = [
        'id', 'name',
    ];
	
	protected $table = 'question_category';
	
	public function getAccessibilityRating($ratingSystem)
	{
		if (!isset($this->rating))
			$this->rating = rand(0, 100);
		
		return $this->rating;
	}
	
    public function questions()
    {
        return $this->hasMany('App\Question');
    }	
	
}
