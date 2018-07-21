<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Image extends Model
{
    protected $fillable = [
        'id', 'location_id', 'raw_data',
    ];
    public $incrementing = false;

    protected $table = 'image';

    public function __construct()
    {
        $this->attributes = array('id' => Uuid::generate(4)->string);
    }
}
