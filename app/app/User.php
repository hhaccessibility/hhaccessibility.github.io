<?php

namespace App;
use Eloquent;
use Illuminate\Support\Facades\Hash;

class User extends Eloquent
{
    protected $fillable = [
        'email', 'password_hash', 'search_radius_km', 
		'longitude', 'latitude', 'remember_token',
		'home_city','home_zipcode','home_region',"home_country_id"
    ];
	public $timestamps = false;
	
	protected $table = 'user';
	
	public function roles()
	{
	   return $this->belongsToMany(Role::class, 'user_role');
	}
	
	public static function isQuestionRequired($required_questions, $id)
	{
		foreach ($required_questions as $question)
		{
			if ( $question->id === $id )
				return true;
		}
		return false;
	}
	
	public function requiredQuestions()
	{
	   return $this->belongsToMany(Question::class, 'user_question');		
	}
	
	public static function generateSaltedHash($password) {
		return Hash::make($password);
	}
}
