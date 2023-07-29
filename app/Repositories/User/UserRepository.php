<?php

namespace App\Repositories\User;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterFace {
	public function index (): Collection {
		return $this->getQuery()
			->latest()
			->get();
	}

	public function store ( array $data ): Model {
		// TODO: Implement store() method.
		$user = new User();
		return $this->extracted($user,$data);
	}

	public function getUserById ( int $userId ): User|ModelNotFoundException {

		return  $this->getQuery()->findOrFail($userId);
	}

	public function update ( array $data , int $userId ): Model {
		$user = $this->getUserById($userId);
		return $this->extracted($user,$data);
	}

	public function destroy ( User $user ): void {
		$user->delete();

	}


	protected function extracted(User $user, array $data) {
		$user->fill($data);
		if (isset($data['password'])) {
			$user->password = Hash::make($data['password']);
		}
        if(isset($data['role_name'])) {
            $user->syncRoles($data['role_name']);
        }
		$user->save();
		return $user;
	}

	protected function getQuery() {
		return User::query();
	}
}
