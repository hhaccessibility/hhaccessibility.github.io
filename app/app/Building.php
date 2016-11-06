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
}
