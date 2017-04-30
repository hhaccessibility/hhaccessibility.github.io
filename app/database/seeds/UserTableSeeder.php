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
		$users = SeedHelper::readTableData('user.json');
		$user_roles = [];
		// set passwords.
		foreach ($users as &$user)
		{
			$user['password_hash'] = User::generateSaltedHash('password');
			$user_roles []= [
				'user_id' => $user['id'],
				'role_id' => Role::GENERAL_SEARCH_AND_REVIEW
			];
		}
		DB::table('user')->insert($users);
		$user_roles []= [
			'user_id' => 1,
			'role_id' => Role::INTERNAL
		];

		DB::table('user_role')->insert($user_roles);
	}

}