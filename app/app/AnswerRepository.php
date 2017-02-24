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
		if ( !Session::has('answers') )
		{
			Session::put('answers', []);
		}
		$answers = Session::get('answers');
		if ( !array_key_exists($location_id, $answers) )
		{
			$answers[$location_id] = [];
		}
		
		Session::put('answers', $answers);
		
		return $answers[$location_id];
	}

	public static function saveAnswer(int $location_id, int $question_id, int $answer_value)
	{
		$location_answers = AnswerRepository::getAnswersForLocation($location_id);
		$location_answers[$question_id] = $answer_value;
		
		Session::put('answers', $location_answers);
	}

	public static function saveComment(int $location_id, int $question_category_id, string $comment)
	{
		$location_answers = AnswerRepository::getAnswersForLocation($location_id);
		if ( !array_key_exists('comments', $location_answers) )
		{
			$location_answers['comments'] = [];
			Session::put('answers', $location_answers['comments']);
		}
		$location_comments = $location_answers['comments'];
		$location_comments[$question_category_id] = $comment;
	}
	
	public function getComment()
	{
		if ( !Session::has('answers') )
			return 'no_answers_key';
		
		$answers = Session::get('answers');
		if ( !array_key_exists($this->location_id, $answers) )
			return 'no_location_id_key.  '. print_r($answers, true);
		
		$location_answers = Session::get('answers')[$this->location_id];
		if ( !array_key_exists('comments', $location_answers) )
			return 'no_comments_key';
		$comments = $location_answers['comments'];
		if ( !array_key_exists($this->question_category_id,  $comments) )
			return 'no_question_category_match';
		
		return $comments[$this->question_category_id];
	}

	/**
	Copies answers from session to database
	*/
	public static function commitAnswersForLocation(int $location_id)
	{
		if ( Session::has('answers') && array_key_exists($location_id, Session::get('answers')) )
		{
			$location_answers = Session::get('answers')[$location_id];
			// loop through answers.
			foreach ($location_answers as $key=>$value)
			{
				if ( $key === 'comments' )
					continue;
				
				// $key is the id of a question.
				
			}
			
			// loop through comments.
		}
	}

}