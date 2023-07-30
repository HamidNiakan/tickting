<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnums;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employesses = [
			[
				'id' => 1,
				'first_name' => 'hamid',
				'last_name' => 'niakan',
				'mobile' => '09178223037',
				'email' => 'hamid@gmail.com',
				'password' => Hash::make('12345')
			],
			[
				'id' => 2,
				'first_name' => 'karim',
				'last_name' => 'niakan',
				'mobile' => '09178223035',
				'email' => 'karim@gmail.com',
				'password' => Hash::make('12346')
			],
			[
				'id' => 3,
				'first_name' => 'hassan',
				'last_name' => 'niakan',
				'mobile' => '09178223039',
				'email' => 'karimm@gmail.com',
				'password' => Hash::make('12346')
			],
		];
		
		foreach ($employesses as $item) {
			$user = User::query()
				->updateOrCreate(['id' => $item['id']],$item);
			$user->syncRoles(UserRoleEnums::Employee->value);
		}
    }
}
