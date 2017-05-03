<?php

namespace App;
use Eloquent;

class ReviewComment extends Eloquent
{
    protected $fillable = [
        'content', 'answered_by_user_id', 'question_category_id', 'location_id'
    ];
	protected $table = 'review_comment';

	public function getAnsweredByUser()
	{
		if ( !isset($this->answered_by_user) )
			$this->answered_by_user = $this->answeredByUser()->first();

		return $this->answered_by_user;
	}
	
	public function answeredByUser()
	{
        return $this->belongsTo('App\User');
	}
	
}
