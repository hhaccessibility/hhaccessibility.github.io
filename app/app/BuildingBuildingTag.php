<?php

namespace App;
use Eloquent;

class BuildingBuildingTag extends Eloquent
{
    protected $fillable = [
        'building_id', 'building_tag_id',
    ];
	
	protected $table = 'building_building_tag';
}
