<?php namespace App\Http\Controllers;

use App\Location;
use App\QuestionCategory;
use App\BaseUser;
use App\ReviewComment;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class LocationRatingController extends Controller {

   public function show(int $location_id, int $question_category_id = null)
   {
	   $location = Location::find($location_id);
	   $question_categories = QuestionCategory::with('questions')->get();
	   $question_category = null;
	   if ( $question_category_id !== null )
	   {
			$question_category = QuestionCategory::find($question_category_id);
	   }
	   
	   return view('pages.location_rating.rate', [
			'location' => $location,
			'question_category' => $question_category,
			'question_categories' => $question_categories
	   ]);
   }

}