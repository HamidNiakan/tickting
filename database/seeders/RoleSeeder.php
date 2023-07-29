<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		$roles = [
			[
				'id' => 1,
				'name' => 'manager',
			],
			[
				'id' => 2,
				'name' => 'employee'
			],
			[
				'id' => 3,
				'name' => 'user'
			]
		];
		
		foreach ($roles as $item) {
			Role::query()
				->updateOrCreate(['id' => $item['id']],$item);
		}

    }
}
