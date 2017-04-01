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
	public function commitReview(Request $request)
	{
		$validation_rules = [
			'location_id' => 'required|integer'
		];
		$validator = Validator::make(Input::all(), $validation_rules);
		if ($validator->fails())
		{
			return response(422)->json(['success' => false]);
		}
		
		$location_id = Input::get('location_id');
		AnswerRepository::commitAnswersForLocation($location_id);
		
		return redirect()->intended('/reviewed-locations');
	}

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

	public function submit(Request $request)
	{
		$validation_rules = [
			'location_id' => 'required|integer'
		];
		$validator = Validator::make(Input::all(), $validation_rules);
		if ($validator->fails())
		{
			return response(422)->json(['success' => false]);
		}
		AnswerRepository::commitAnswersForLocation(Input::get('location_id'));
		return response()->json(['success' => true]);
	}

	public function show(int $location_id, int $question_category_id = null)
	{
	   $location = Location::find($location_id);
	   $question_categories = QuestionCategory::with('questions')->get();
	   $question_category = null;
	   $next_question_category_id = null;

	   // If no category is specified, pick the first one.
	   if ( $question_category_id === null && !empty($question_categories) )
	   {
			$question_category_id = $question_categories[0]->id;
	   }
	   if ( $question_category_id !== null )
	   {
			$question_category = QuestionCategory::find($question_category_id);
	   }
	   if ( $question_category_id )
	   {
			$next_question_category = QuestionCategory::where('id', '>', $question_category_id)
				->first();
			if ( $next_question_category )
			{
				$next_question_category_id = $next_question_category->id;
			}
	   }
	   
	   return view('pages.location_rating.rate', [
			'location' => $location,
			'question_category' => $question_category,
			'question_categories' => $question_categories,
			'answer_repository' => new AnswerRepository($location_id, $question_category_id),
			'next_question_category_id' => $next_question_category_id
	   ]);
   }

   public function reviewedLocations()
   {
	   $location_ids = AnswerRepository::getReviewedLocations();
	   $locations = Location::whereIn('id', $location_ids)->get();
	   return view('pages.location_rating.reviewed_locations', [
			'locations' => $locations
		   ]);
   }
}