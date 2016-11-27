<?php

namespace App;
use Eloquent;

class Location extends Eloquent
{
    protected $fillable = [
        'name', 'longitude', 'latitude', 'owner_user_id', 'data_source_id',
    ];
	public $timestamps = false;

	protected $table = 'location';

    /**
     * The tags that belong to this location.
     */
    public function tags()
    {
        return $this->belongsToMany('App\LocationTag');
    }	
}
