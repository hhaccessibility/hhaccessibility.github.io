<?php

namespace App;
use Eloquent;

class LocationLocationTag extends Eloquent
{
    protected $fillable = [
        'location_id', 'location_tag_id',
    ];
	
	protected $table = 'location_location_tag';
}
