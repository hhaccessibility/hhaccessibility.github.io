<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Libraries\Gis;
use App\Location;

class PWAController extends Controller
{
    public function manifest()
    {
        return response()->json([
            "dir" => "ltr",
            "lang" => "en",
            "name" => "AccessLocator",
            "display" => "fullscreen",
            "start_url" => "/?using_pwa=1",
            "short_name" => "AccessLocator",
            "background_color" => "#e7f3f5",
            "theme_color" => "#202767",
            "description" => "Your personalized access to the world",
            "orientation" => "portrait",
            "background_color" => "#202767",
            "related_applications" => [],
            "prefer_related_applications" => false,
            "icons" => [
                [
                    "src" => "/images/logo-192x192.png",
                    "type" => "image/png",
                    "sizes" => "192x192"
                ],
                [
                    "src" => "/images/logo-512x512.png",
                    "type" => "image/png",
                    "sizes" => "512x512"
                ]
            ]
        ]);
    }

    public function getNearbyLocationToRate(float $longitude, float $latitude)
    {
        $radiusMeters = 20;
        $locationsQuery = Location::query();
        $search_results = \App\Libraries\Gis::findLocationsWithinRadius(
            $latitude,
            $longitude,
            $radiusMeters,
            $locationsQuery
        );
        $locations = [];
        foreach ($search_results as $search_result) {
            $locations []= $search_result;
        }
        $not_found_response = response()->json([
                'message' => 'No location found close enough.'
            ], 404);

        if (empty($locations)) {
            return $not_found_response;
        }
        \App\Libraries\Gis::updateDistancesFromPoint($longitude, $latitude, $locations);
        $location = $locations[0];
        foreach ($locations as $location_) {
            if ($location->distance > $location_->distance) {
                $location = $location_;
            }
        }
        if ($location->getNumberOfUsersWhoRated() > 0) {
            return $not_found_response;
        }

        return response()->json([
            'name' => $location->name,
            'id' => $location->id
        ]);
    }
}
