<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const GUEST = 1;
    const GENERAL_SEARCH_AND_REVIEW = 2;
    const INTERNAL = 4;
    
    protected $fillable = [
        'name', 'description',
    ];
    
    public $timestamps = false;
    protected $table = 'role';
}
