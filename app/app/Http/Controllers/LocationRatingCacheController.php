<?php namespace App\Http\Controllers;

/*
The LocationRatingCacheController is used to refresh the ratings cache after a deployment or
when initializing a new development environment.  Having an empty cache makes
*/

use App\Location;
use App\QuestionCategory;
use App\Question;
use App\AnswerRepository;
use App\LocationGroup;
use App\Helpers\RatingCacheHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class LocationRatingCacheController extends Controller
{
    public function populateRatingsCache()
    {
        $questions = Question::all();

        AnswerRepository::clearUnratedLocations();
        // get up to 10 locations that have not been rated.
        $locations = Location::where('ratings_cache', '=', null)->
            limit(10)->get();
        foreach ($locations as $location) {
            AnswerRepository::updateRatingsCache($location->id, $questions);
        }
        $locations_unrated = Location::where('ratings_cache', '=', null)->count();
        return response()->json([
            'number_rated' => count($locations),
            'number_unrated' => $locations_unrated
        ]);
    }

    public function populateUngroupedLocationGroupRatingsCache()
    {
        $location_group = RatingCacheHelper::build()->updateRootRatingsCache();

        return response()->json($location_group);
    }

    public function populateLocationGroupRatingsCache(Request $request)
    {
        $location_group_id = $request->input('location_group_id');
        $location_group = [];
        $location_group = RatingCacheHelper::build()->updateRatingsCacheForGroup($location_group_id);

        return response()->json($location_group);
    }
}
