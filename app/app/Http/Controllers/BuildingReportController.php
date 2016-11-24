<?php namespace App\Http\Controllers;

use App\Building;
use App\QuestionCategory;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class BuildingReportController extends Controller {

    public function show($building_id)
    {
		$building = Building::find($building_id);
		$question_categories = QuestionCategory::with('questions')->get();
		
		return view('pages.building_report', [
			'building' => $building,
			'question_categories' => $question_categories]
		);
    }

}