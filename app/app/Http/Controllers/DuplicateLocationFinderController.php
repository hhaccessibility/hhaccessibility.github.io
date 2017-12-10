<?php namespace App\Http\Controllers;

use App\BaseUser;
use App\User;
use App\Libraries\Gis;
use Illuminate\Routing\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use DB;

function compareByDistance($loc1, $loc2)
{
	($loc1->distance) - ($loc2->distance);
	if( $loc1->distance < $loc2->distance )
		return -1;
	else if ( $loc1->distance > $loc2->distance )
		return 1;
	else
		return 0;
}

function getPathForTable($tableName)
{
	$dir = getcwd();
	$dir = realpath($dir.'\\..');
	return $dir . "\\database\\seeds\\data\\" . $tableName . '.json';
}

function getDataForTable($tableName)
{
	$content = file_get_contents(getPathForTable($tableName));
	$result = json_decode($content, true, null, JSON_FLOAT_AS_STRING);
	
	
	return $result;
}

function singleLineStringifier($record)
{
	$new_record_json = "{";
	foreach ($record as $key => $value)
	{
		if ( $new_record_json !== "{" )
		{
			$new_record_json .= ", ";
		}
		$new_record_json .= json_encode($key).': '. json_encode($value, JSON_UNESCAPED_SLASHES);
	}
	$new_record_json .= "}";
	return $new_record_json;
}

function multilineStringifier($record)
{
	$new_record_json = "{\n";
	foreach ($record as $key => $value)
	{
		if ( $new_record_json !== "{\n" )
		{
			$new_record_json .= ",\n";
		}
		$new_record_json .= '        '.json_encode($key).': '. json_encode($value, JSON_UNESCAPED_SLASHES);
	}
	$new_record_json .= "\n    }";
	return '    '.$new_record_json;
}

function writeTableData($tableName, $data)
{
	$fp = fopen(getPathForTable($tableName), 'w');
	$new_content = "[\n";
	foreach ($data as $record)
	{
		if ( $new_content !== "[\n" )
		{
			$new_content .= ",\n";
		}
		if ( $tableName === 'location_duplicate' )
		{
			$new_record_json = singleLineStringifier($record);
		}
		else {
			$new_record_json = multilineStringifier($record);
		}
		$new_content .= $new_record_json;
	}
	$new_content .= "\n]";
	fwrite($fp, $new_content);
	fclose($fp);
}

class DuplicateLocationFinderController extends Controller
{
	public function markConfirmedDuplicate(Request $request)
	{
		if ( !BaseUser::isInternal() ) {
			throw new AuthenticationException('Must be internal user');
		}
		$validation_rules = [
			'location_ids' => 'required'
		];
		$validator = Validator::make(Input::all(), $validation_rules);
		$location_ids = Input::get('location_ids');
		if ($validator->fails())
		{
			return response(422)->json(['success' => false, 'msg' => 'location_ids must be set']);
		}
		if ( !is_array($location_ids) || count($location_ids) < 2 )
		{
			return response(422)->json(['success' => false, 'msg' => 'location_ids must be an array with at least 2 elements']);
		}
		
		$location_that_stays = $location_ids[0];
		$location_ids_to_delete = [];
		foreach ($location_ids as $location_id)
		{
			if( $location_that_stays > $location_id )
			{
				$location_that_stays = $location_id;
			}
		}
		foreach ($location_ids as $location_id)
		{
			if( $location_id !== $location_that_stays )
			{
				$location_ids_to_delete []= $location_id;
			}
		}
		
		// Update location_duplicate.json accordingly.
		if( config('app.update_seed_files') )
		{
			$location_duplicates = getDataForTable('location_duplicate');
			$location_duplicates = array_filter($location_duplicates, function($location_duplicate) use($location_ids_to_delete) {
				return !in_array($location_duplicate['location_id'], $location_ids_to_delete);
			});
			writeTableData('location_duplicate', $location_duplicates);
		}

		Db::table('location_location_tag')->whereIn('location_id', $location_ids_to_delete)->delete();
		Db::table('location')->whereIn('id', $location_ids_to_delete)->delete();
		
		// Update location_location_tag.json and location.json accordingly.
		if( config('app.update_seed_files') )
		{
			$location_location_tags = getDataForTable('location_location_tag');
			$locations = getDataForTable('location');
			$location_location_tags = array_filter($location_location_tags, function($location_location_tag) use($location_ids_to_delete) {
				return !in_array($location_location_tag['location_id'], $location_ids_to_delete);
			});
			$locations = array_filter($locations, function($location) use($location_ids_to_delete) {
				return !in_array($location['id'], $location_ids_to_delete);
			});
			writeTableData('location', $locations);
			writeTableData('location_location_tag', $location_location_tags);
		}
		
		return response()->json(['success' => true]);
	}
	
	public function showDuplicateLocationFinder(Request $request)
	{
		$radiusMeters = 200;
		if ( Input::has('radius_meters') ) {
			$radiusMeters = intval(Input::get('radius_meters'));
		}
		$location_id = Input::get('location_id');
		$location = DB::table('location')->find($location_id);
		if ( !$location )
		{
			return Redirect::to('');
		}
		$locationQuery = DB::table('location')->where('id', '<>', $location_id);
		$search_results = \App\Libraries\Gis::findLocationsWithinRadius($location->latitude, $location->longitude, $radiusMeters, $locationQuery);
		$loc_array = [];
		foreach ($search_results as $loc)
		{
			$loc_array []= $loc;
		}
		$search_results = $loc_array;

		\App\Libraries\Gis::updateDistancesFromPoint($location->longitude, $location->latitude, $search_results);
		$search_results = \App\Libraries\Gis::filterTooDistant($search_results, $radiusMeters / 1000);
		
		usort($search_results, 'App\Http\Controllers\compareByDistance');
		
		$viewData = [
			'radius_meters' => $radiusMeters,
			'location' => $location,
			'search_results' => $search_results
		];
		
		return view('pages.internal_features.duplicate_finder', $viewData);
	}
}