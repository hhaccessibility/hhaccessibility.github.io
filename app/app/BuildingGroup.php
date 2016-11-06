<?php

namespace App;
use Eloquent;

class BuildingGroup extends Eloquent
{
    protected $fillable = [
        'name',
    ];
	public $timestamps = false;
	
	protected $table = 'building_group';	
}
