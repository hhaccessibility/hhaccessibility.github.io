<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Region extends Model
{
    protected $fillable = [
        'name', 'country_id',
    ];
    public $timestamps = false;

    protected $table = 'region';
}
