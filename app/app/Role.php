<?php

namespace App;
use Eloquent;

class Role extends Eloquent
{
    protected $fillable = [
        'name', 'description',
    ];
	
	protected $table = 'role';	
}
