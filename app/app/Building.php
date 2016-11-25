<?php

namespace App;
use Eloquent;

class Building extends Eloquent
{
    protected $fillable = [
        'name', 'longitude', 'latitude', 'owner_user_id', 'data_source_id',
    ];
	public $timestamps = false;

	protected $table = 'building';

    /**
     * The tags that belong to this building.
     */
    public function tags()
    {
        return $this->belongsToMany('App\BuildingTag');
    }	
}
