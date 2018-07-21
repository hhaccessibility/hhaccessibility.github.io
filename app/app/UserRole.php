<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class UserRole extends Model
{
    protected $fillable = [
        'user_id', 'role_id',
    ];
    public $timestamps = false;
    
    protected $table = 'user_role';

    public function __construct()
    {
        $this->attributes = array('id' => Uuid::generate(4)->string);
    }
}
