<?php

namespace App\Helpers;

use App\LocationGroup;
use App\Question;
use DB;
use Exception;

class RatingCacheHelper
{
    public static function build()
    {
        return app(RatingCacheHelper::class);
    }

    public function updateRatingsCacheForGroup($location_group_id)
    {
        // Get the location group who's ratings cache is root of all other caches.
        $root_location_group = LocationGroup::getRootLocationGroup();
        $root_ratings = [];
        if ($root_location_group->ratings_cache) {
            $root_ratings = json_decode($root_location_group->ratings_cache, true);
        }
        $location_group = LocationGroup::where('id', '=', $location_group_id)->first();
        $questions = Question::all();
        $new_ratings_cache = [];

        foreach ($questions as $question) {
            $locations_with_question_rated = DB::table('location')
                ->join('user_answer', 'user_answer.location_id', '=', 'location.id')
                ->where('user_answer.question_id', '=', $question->id)
                ->where('location.location_group_id', '=', $location_group_id)
                ->whereNotNull('location.ratings_cache')
                // when this runs, the ratings cache should be populated enough to work well.
                ->whereNull('user_answer.deleted_at') // ignore the non-last answers from users.
                ->where('user_answer.answer_value', '!=', 3) // ignore the 'Didn't look' answers.
                ->distinct()
                ->get(['location.id', 'location.ratings_cache']);
            $total_rating = 0;
            if (count($locations_with_question_rated) === 0) {
                // Get the rating from the root location group, if possible.
                if (!isset($root_ratings['' . $question->id])) {
                    $root_ratings['' . $question->id] = 0;
                }
                $new_ratings_cache['' . $question->id] = $root_ratings['' . $question->id];
            } else {
                foreach ($locations_with_question_rated as $location) {
                    $ratings_cache = json_decode($location->ratings_cache, true);
                    $rating = $ratings_cache['' . $question->id];
                    $total_rating += $rating;
                }
                $new_ratings_cache['' . $question->id] = $total_rating * 1.0 / count($locations_with_question_rated);
            }
        }
        $location_group->ratings_cache = json_encode($new_ratings_cache);
        $location_group->save();

        return $location_group;
    }

    public function updateRootRatingsCache()
    {
        // Get the location group who's ratings cache is root of all other caches.
        $location_group = LocationGroup::getRootLocationGroup();

        $questions = Question::all();
        $new_ratings_cache = [];

        // For every question, find the average rating across all locations that actually have answers on it.
        // If a question has no answer in any location, default to inaccessible.
        foreach ($questions as $question) {
            $locations_with_question_rated = DB::table('location')
                ->join('user_answer', 'user_answer.location_id', '=', 'location.id')
                ->where('user_answer.question_id', '=', $question->id)
                ->whereNotNull('location.ratings_cache')
                // when this runs, the ratings cache should be populated enough to work well.
                ->whereNull('user_answer.deleted_at') // ignore the non-last answers from users.
                ->where('user_answer.answer_value', '!=', 3) // ignore the 'Didn't look' answers.
                ->distinct()
                ->get(['location.id', 'location.ratings_cache']);
            $total_rating = 0;
            if (count($locations_with_question_rated) === 0) {
                $new_ratings_cache['' . $question->id] = 0;
            } else {
                foreach ($locations_with_question_rated as $location) {
                    $ratings_cache = json_decode($location->ratings_cache, true);
                    if (!isset($ratings_cache['' . $question->id])) {
                        throw new Exception('question ' . $question->id .
                            ' is not set in ratings_cache for location id: '.$location->id);
                    }
                    $rating = $ratings_cache['' . $question->id];
                    if ($rating > 1) {
                        $rating = 1;
                    }
                    $total_rating += $rating;
                }
                $new_ratings_cache['' . $question->id] = $total_rating * 100.0 / count($locations_with_question_rated);
            }
        }
        $location_group->ratings_cache = json_encode($new_ratings_cache);
        $location_group->save();

        return $location_group;
    }
}
