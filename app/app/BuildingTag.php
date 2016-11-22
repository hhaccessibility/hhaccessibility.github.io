<?php

namespace App;
use Eloquent;

class BuildingTag extends Eloquent
{
    protected $fillable = [
        'name', 'description',
    ];
	
	protected $table = 'building_tag';
}
