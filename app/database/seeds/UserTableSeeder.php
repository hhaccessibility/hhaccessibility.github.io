<?php
use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use Webpatser\Uuid\Uuid;

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
				'id' => Uuid::generate(4)->string,
				'user_id' => $user['id'],
				'role_id' => Role::GENERAL_SEARCH_AND_REVIEW
			];
		}
		DB::table('user')->insert($users);
		$user_roles []= [
			'id' => Uuid::generate(4)->string,
			'user_id' => '00000000-0000-0000-0000-000000000001',
			'role_id' => Role::INTERNAL
		];

		DB::table('user_role')->insert($user_roles);
	}

}