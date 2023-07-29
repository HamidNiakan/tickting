<?php

namespace App\Repositories\User;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface UserRepositoryInterFace {
	
	public function index():Collection;
	
	public function store(array $data):Model;
	
	public function getUserById(int $userId):User|ModelNotFoundException;
	
	public function update(array $data, int $userId):Model;
	
	public function destroy(User $user):void;
}