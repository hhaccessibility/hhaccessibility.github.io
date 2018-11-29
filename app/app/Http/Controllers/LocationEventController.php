<?php namespace App\Http\Controllers;

use App\LocationEvent;
use Illuminate\Routing\Controller;

class LocationEventController extends Controller
{
    public function show(string $location_event_id)
    {
        $location_event = LocationEvent::find($location_event_id);
        if (!$location_event) {
            abort(404, 'Specified location event not found');
        }

        return view('pages.location_report.location_event', [
            'location_event' => $location_event,
            'location_id' => $location_event->getLocationId()
        ]);
    }
}
