<?php

namespace App;
use Eloquent;
use DateTime;
use DateTimeZone;

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
	
	public function getWhenSubmitted()
	{
		// UTC is the time zone we assume the when_submitted is saved with.
		$new_str = new DateTime($this->when_submitted, new DateTimeZone('UTC') );
		// time zone for display in Windsor, Ontario.
		$new_str->setTimeZone(new DateTimeZone( 'America/Toronto' ));
		return $new_str->format('Y-M-d h:i a');
	}
}
