<?php

namespace Tests\Feature\Concerns;
use App\Enums\UserRoleEnums;
use App\Models\User;

trait TestingUser {
	public function createUser(string $mobile,string $password,string $userRole = 'user') {
		$user = User::factory()
					->setMobile($mobile)
					->setPassword($password)
					->create();
		$user->assignRole($userRole);
		return $user;
	}
	
	public function createEmployee(string $mobile,string $password) {
		$user = User::factory()
					->setMobile($mobile)
					->setPassword($password)
					->create();
		$user->assignRole(UserRoleEnums::Employee->value);
		return $user;
	}
	
	
	
	
	
}