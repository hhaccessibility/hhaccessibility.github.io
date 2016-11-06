<?php
use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('user')->delete();
		$newUser = User::create(array(
			'username' => 'test',
			'password_hash' => User::generateSaltedHash('password')
		));
		DB::table('user_role')->insert(
            [
                'user_id' => $newUser->id,
                'role_id' => Role::GENERAL_SEARCH_AND_REVIEW,
            ]
        );		
	}

}