<?php

namespace App;
use Eloquent;

class Building extends Eloquent
{
    protected $fillable = [
        'name', 'longitude', 'latitude', 'owner_user_id',
    ];
	
	protected $table = 'building';	
}
