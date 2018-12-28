<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $table = 'suggestion';
    protected $fillable = [
        'location_name','location_external_url','location_address','location_phone_number'
    ];
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    public $timestamps = false;
    protected $softDelete = true;
    protected $dates = ['deleted_at'];

    public function belongsToLocation()
    {
        return $this->belongsTo('Location', 'id', 'id');
    }

    public function belongsToUser()
    {
        return $this->belongsTo('User', 'id', 'id');
    }
}
