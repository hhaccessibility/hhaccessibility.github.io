<?php

namespace App;
use Eloquent;

class UserRole extends Eloquent
{
    protected $fillable = [
        'user_id', 'role_id', 
    ];
	public $timestamps = false;
	
	protected $table = 'user_role';
}
