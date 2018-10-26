<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationGroup extends Model
{
    protected $fillable = [
        'name', 'ratings_cache', 'is_automatic_group'
    ];
    public $timestamps = false;
    
    protected $table = 'location_group';

    public static function getRootLocationGroup()
    {
        return LocationGroup::where('id', '=', 401)->where('is_automatic_group', '=', true)->first();
    }
}
