<?php
use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('user_role')->delete();
		DB::table('user')->delete();
		$newUser = User::create(array(
			'id' => 1,
			'password_hash' => User::generateSaltedHash('password'),
			'email' => 'josh.greig2@gmail.com',
			'first_name' => 'John',
			'last_name' => 'Smith',
			'home_city' => 'Windsor',
			'home_region' => 'Ontario',
			'home_country_id' => 39
		));
		DB::table('user_role')->insert(
            [
                'user_id' => $newUser->id,
                'role_id' => Role::GENERAL_SEARCH_AND_REVIEW,
            ]
        );		
	}

}