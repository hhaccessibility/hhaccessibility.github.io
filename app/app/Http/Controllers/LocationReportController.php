<?php namespace App\Http\Controllers;

use App\Location;
use App\QuestionCategory;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class LocationReportController extends Controller {

    public function show($location_id)
    {
		$location = Location::find($location_id);
		$question_categories = QuestionCategory::with('questions')->get();
		
		return view('pages.location_report', [
			'location' => $location,
			'question_categories' => $question_categories]
		);
    }

}