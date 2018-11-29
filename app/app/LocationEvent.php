<?php

namespace App;

use App\Location;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Exception;

class LocationEvent extends Model
{
    protected $fillable = [
        'name', 'description', 'when', 'external_web_url'
    ];

    public $timestamps = false;
    protected $table = 'location_event';
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function __construct()
    {
        $this->attributes = array('id' => Uuid::generate(4)->string);
    }

    public function getFormattedDate()
    {
        return date_format(date_create($this->when), 'F j, Y');
    }

    public function getLocationId()
    {
        $location = Location::where('destroy_location_event_id', '=', $this->id)
            ->distinct()
            ->get(['id'])->first();
        if (!$location) {
            throw new Exception('Unable to find a location that references location event ' . $this->id);
        }
        return $location->id;
    }
}
