<?php namespace App\Http\Controllers;

use DB;

class QuestionExplanationController extends Controller {
	
	public function show($question_id, $location_id = '')
	{
		$question = DB::table('question')->find($question_id);
		if (!$question)
		{
			abort(404, 'Specified question not found.');
		}
		$location = null;
		if ($location_id !== '')
		{
			$location = DB::table('location')->find($location_id);
			if (!$location)
			{
				abort(404, 'Specified location not found.');
			}
		}
		$question_category = DB::table('question_category')->find($question->question_category_id);
		
		return view('pages.questions.question_explanation', [
			'question' => $question,
			'question_category' => $question_category,
			'location' => $location
		]);
	}
}