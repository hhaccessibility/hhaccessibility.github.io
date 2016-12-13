<?php

namespace App;
use Eloquent;

class Question extends Eloquent
{
    protected $fillable = [
        'question_html',
    ];
	
	protected $table = 'question';
	
	public function getAccessibilityRating($location_id, $ratingSystem)
	{
		if (!isset($this->rating))
			$this->rating = rand(0, 100);
		
		return $this->rating;
	}
	
}
