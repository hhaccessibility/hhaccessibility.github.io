<?php

namespace App;
use Eloquent;

class Building extends Eloquent
{
    protected $fillable = [
        'name', 'longitude', 'latitude', 'owner_user_id',
    ];
	public $timestamps = false;

	protected $table = 'building';

    /**
     * The tags that belong to a building.
     */
    public function tags()
    {
        return $this->belongsToMany('building_tag', 'building_building_tag');
    }	
}
