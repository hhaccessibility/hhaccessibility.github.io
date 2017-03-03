<?php

namespace App;
use Session;
use App\ReviewComment;

class AnswerRepository
{
	public function __construct(int $location_id, int $question_category_id)
	{
		$this->location_id = $location_id;
		$this->question_category_id = $question_category_id;
	}
	
	private static function getAnswersForLocation(int $location_id)
	{
		if ( !Session::has('answers_'.$location_id) )
		{
			Session::put('answers_'.$location_id, []);
		}
		$answers = Session::get('answers_'.$location_id);
		if ( !array_key_exists($location_id, $answers) )
		{
			$answers = [];
		}

		Session::put('answers_'.$location_id, $answers);

		return $answers;
	}

	public static function saveAnswer(int $location_id, int $question_id, int $answer_value)
	{		
		Session::put('answers_'.$location_id.'_'.$question_id, $answer_value);
	}
	
	public static function getAnswer(int $location_id, int $question_id)
	{
		$key = 'answers_'.$location_id.'_'.$question_id;
		if ( !Session::has($key) )
		{
			return '';
		}
		return Session::get($key);
	}

	public static function saveComment(int $location_id, int $question_category_id, string $comment)
	{
		$key = 'answers_'.$location_id.'_comment_'.$question_category_id;
		Session::put($key, $comment);
	}
	
	public function getAnswerForQuestion($question_id)
	{
		return AnswerRepository::getAnswer($this->location_id, $question_id);
	}
	
	public function getComment()
	{
		$key = 'answers_'.$this->location_id.'_comment_'.$this->question_category_id;
		if ( !Session::has($key) )
			return '';
		
		return Session::get($key);
	}
	
	public static function getReviewedLocations()
	{
		$location_ids = AnswerRepository::getUncommittedReviewedLocations();
		
		return $location_ids;
	}

	public static function getUncommittedReviewedLocations()
	{
		$location_ids = [];
		foreach ( Session::all() as $key => $obj )
		{
			if ( strpos($key, 'answers_') === 0 )
			{
				$key = substr($key, strlen('answers_'));
				$index = strpos($key, '_');
				if ( $index > 0 )
				{
					$location_id = intval(substr($key, 0, $index));
					if ( array_search($location_id, $location_ids) === FALSE )
					{
						$location_ids []= $location_id;
					}
				}
			}
		}
		return $location_ids;
	}
	
	public static function destroyUncommittedChanges()
	{
		$answer_keys = [];
		foreach ( Session::all() as $key => $obj )
		{
			if ( strpos($key, 'answers_') === 0 )
			{
				$answer_keys []= $key;
			}
		}
		Session::forget($answer_keys);
	}
	
	/**
	Copies answers from session to database
	*/
	public static function commitAnswersForLocation(int $location_id)
	{
		$location_key = 'answers_'.$location_id.'_';
		foreach ( Session::all() as $key => $obj )
		{
			if ( strpos($key, $location_key) === 0 )
			{
				if ( strpos($key, $location_key.'comment_') === 0 )
				{
					// comment for a question category
					$index = strpos($key, 'comment_');
					$question_category_id = intval(substr($key, $index + strlen('comment_')));
					$comment = $obj;
					
					// If there is already a comment from this user to $question_category_id, update it.
					// FIXME: save comment to review_comment table.
				}
				else
				{
					// answer
				}
			}
		}
		
		AnswerRepository::destroyUncommittedChanges();
	}

}