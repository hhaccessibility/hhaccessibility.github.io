<?php namespace App\Http\Controllers;

use App\Location;
use App\QuestionCategory;
use App\Question;
use App\AnswerRepository;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class LocationRatingController extends Controller {

	public function setAnswer(Request $request)
	{
		$validation_rules = [
			'answer'      => 'required|integer|max:2|min:0',
			'question_id' => 'required|integer|min:1',
			'location_id' => 'required|integer'
		];
		$validator = Validator::make(Input::all(), $validation_rules);
		if ($validator->fails())
		{
			return response(422)->json(['success' => false]);
		}

		AnswerRepository::saveAnswer(Input::get('location_id'), Input::get('question_id'), Input::get('answer'));
	
		return response()->json(['success' => true]);
	}
	
	public function removeAnswer(Request $request)
	{
		$validation_rules = [
			'question_id' => 'required|integer|min:1',
			'location_id' => 'required|integer'
		];
		$validator = Validator::make(Input::all(), $validation_rules);
		if ($validator->fails())
		{
			return response(422)->json(['success' => false]);
		}
		
		return response()->json(['success' => true]);
	}
	
	public function setComment(Request $request)
	{
		$validation_rules = [
			'question_category_id' => 'required|integer',
			'location_id' => 'required|integer'
		];
		$validator = Validator::make(Input::all(), $validation_rules);
		if ($validator->fails())
		{
			return response(422)->json(['success' => false]);
		}
		
		AnswerRepository::saveComment(Input::get('location_id'), Input::get('question_category_id'), Input::get('comment'));
		$answer_repo = new AnswerRepository(Input::get('location_id'), Input::get('question_category_id'));
		return response()->json(['success' => true, 'comment' => $answer_repo->getComment()]);
	}

	public function setQuestionAnswerForLocation(int $location_id, int $question_id, int $answer_value)
	{
		// Run some validation checks.
		if ( $answer_value < 0 || $answer_value > 2 )
		{
			return response()->setStatusCode(422, 'answer value is invalid');
		}
		$location = Location::find($location_id);
		if ( $location === null )
		{
			return response()->setStatusCode(404, 'location not found with id ' . $location_id);
		}
		$question = Question::find($question_id);
		if ( $question === null )
		{
			return response()->setStatusCode(404, 'question not found with id ' . $question_id);
		}
		// save answer in session.
		
	}

   public function show(int $location_id, int $question_category_id = null)
   {
	   $location = Location::find($location_id);
	   $question_categories = QuestionCategory::with('questions')->get();
	   $question_category = null;
	   if ( $question_category_id === null && !empty($question_categories) )
	   {
			$question_category_id = $question_categories[0]->id;
	   }
	   if ( $question_category_id !== null )
	   {
			$question_category = QuestionCategory::find($question_category_id);
	   }
	   
	   return view('pages.location_rating.rate', [
			'location' => $location,
			'question_category' => $question_category,
			'question_categories' => $question_categories,
			'answer_repository' => new AnswerRepository($location_id, $question_category_id)
	   ]);
   }

}