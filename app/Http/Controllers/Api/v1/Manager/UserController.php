<?php

namespace App\Http\Controllers\Api\v1\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Manager\CreateUserRequest;
use App\Http\Requests\v1\Manager\UpdateUserRequest;
use App\Http\Resources\v1\UserResource;
use App\Repositories\User\UserRepositoryInterFace;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use function App\Helper\printResult;

class UserController extends Controller
{

	public function __construct (public UserRepositoryInterFace $repository) { }

	public function index() {
		$users = $this->repository->index();
		$users = UserResource::make($users);
		return printResult($users);
	}


	public function store(CreateUserRequest $request) {
		$user = $this->repository->store($request->toArray());
		$user = UserResource::make($user);
		return printResult($user,__('messages.user.store'));

	}

	public function edit(Request $request) {
		$userId = $request->get('userId');
		try {

			$user = $this->repository->getUserById($userId);

			$user = UserResource::make($user);
			return response()->json(['data' =>$user]);
		} catch (ModelNotFoundException $exception) {
			$messages = __('messages.global.error');
			return printResult([],$messages,404);
		}
	}

	public function update(UpdateUserRequest $request) {
		try {
			$userId  = $request->get('userId');
			$user = $this->repository->update($request->toArray(),$userId);
			$user = UserResource::make($user);
			return printResult($user,__('messages.user.update'));
		} catch (ModelNotFoundException $exception) {
			$messages = __('messages.global.error');
			return printResult([],$messages,404);
		}
	}

	public function destroy(Request $request) {
		$userId = $request->get('userId');
		try {
			$user = $this->repository->getUserById($userId);
			$this->repository->destroy($user);
			return printResult([],__('messages.user.destroy'));
		} catch (ModelNotFoundException $exception) {
			$messages = __('messages.global.error');
			return printResult([],$messages);
		}
	}
}
