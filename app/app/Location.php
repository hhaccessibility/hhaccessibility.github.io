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
	
    public function comments()
    {
        return $this->hasMany('App\ReviewComment');
    }
	
	public function locationGroup()
	{
        return $this->belongsTo('App\LocationGroup');
	}
	
	public function getName()
	{
		if ($this->name)
			return $this->name;
		if ($this->location_group_id !== null)
			return $this->locationGroup()->name;
	}
	
	public function getExternalWebURL()
	{
		if (strlen($this->external_web_url) > 3)
		{
			return $this->external_web_url;
		}
		if ($this->location_group_id !== null)
		{
			$group_url = $this->locationGroup()->first()->external_web_url;
			if (strlen($group_url) > 3)
				return $group_url;
		}
		return 'https://www.google.ca/?q=' . urlencode($this->getName() . ' ' . $this->address);
	}
}
