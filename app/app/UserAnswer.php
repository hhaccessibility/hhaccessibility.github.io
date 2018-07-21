<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class UserAnswer extends Model
{
    protected $fillable = [
        'answered_by_user_id', 'question_id', 'location_id', 'answer_value',
    ];
    public $timestamps = false;
    
    protected $table = 'user_answer';
    
    public function __construct()
    {
        $this->attributes = array('id' => Uuid::generate(4)->string);
    }
}
