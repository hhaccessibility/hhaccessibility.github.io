<?php

namespace App;
use Eloquent;

class BuildingGroup extends Eloquent
{
    protected $fillable = [
        'name',
    ];
	
	protected $table = 'building_group';	
}
