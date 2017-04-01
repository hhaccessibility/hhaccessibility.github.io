<?php

namespace App;
use Eloquent;

class ReviewComment extends Eloquent
{
    protected $fillable = [
        'content', 'answered_by_user_id', 'question_category_id', 'location_id'
    ];
	
	protected $table = 'review_comment';
	
}
