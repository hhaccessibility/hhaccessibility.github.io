<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

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
            "theme_color" => "#202767",
            "description" => "Your personalized access to the world",
            "orientation" => "portrait",
            "background_color" => "#202767",
            "related_applications" => [],
            "prefer_related_applications" => false,
            "icons" => [
                "src" => "/images/logo-192x192.png",
                "type" => "image/png",
                "sizes" => "192x192"
            ]
        ]);
    }
}
