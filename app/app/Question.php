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
		$answers = $this->answers()
			->where('location_id', '=', $location_id)
			->get();
		$sum = 0;
		$totalCount = 0;
		foreach ($answers as $answer)
		{
			$individualRating = intval($answer->answer_value);
			
			// count N/A the same as yes(1).
			if ($individualRating === 2)
				$individualRating = 1;
			
			$sum = $sum + $individualRating;
			$totalCount = $totalCount + 1;
		}
		if ($totalCount === 0)
			return 0;
		else
			return $sum * 100 / $totalCount;
	}

	public function answers()
	{
        return $this->hasMany('App\UserAnswer');		
	}
}
