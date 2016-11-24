<?php

namespace App;
use Eloquent;

class BuildingTag extends Eloquent
{
    protected $fillable = [
        'name', 'description',
    ];
	
	protected $table = 'building_tag';
	
    public function buildings()
    {
        return $this->belongsToMany('App\Building');
    }
}
