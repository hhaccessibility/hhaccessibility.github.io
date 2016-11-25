<?php

namespace App;
use Eloquent;

class DataSource extends Eloquent
{
    protected $fillable = [
        'name', 'description',
    ];
	public $timestamps = false;

	protected $table = 'data_source';

    /**
     * The buildings that belong to this data source
     */
    public function buildings()
    {
        return $this->belongsToMany('App\Building');
    }
}
