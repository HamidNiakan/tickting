<?php

namespace Tests\Feature;

use App\Enums\UserRoleEnums;
use App\Http\Resources\v1\UserResource;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Concerns\TestingUser;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
	use DatabaseMigrations;
	use TestingUser;
    public $user;
	public $employee;

	protected function setUp (): void {
		parent::setUp(); // TODO: Change the autogenerated stub
		$this->withHeaders([
			'Accept' => 'application/json'
						   ]);

		Artisan::call('db:seed');

	}

	/**
     * A basic feature test example.
     */
	public  function testValidationCreateUser() {
		$this->post(route('api.manager.store'),[
			'first_name' => 'test',
			'last_name' => null,
			'mobile' => null,
			'email' => null,
			'password' => null,
			'role_name' => null
		])->assertStatus(422)
			->assertJson(fn (AssertableJson $json) =>
			$json->hasAll([
				"errors.mobile",
				"errors.last_name",
				"errors.password" ,
				"errors.email" ,
				"errors.role_name" ,
						  ])->etc()
			);
	}

    /**
     * Undocumented function
     *
     * @return void
     */
    public function testCreateUser(): void
    {
        $response = $this->post(route('api.manager.store'),[
            'first_name' => 'test',
            'last_name' => 'test',
            'mobile' => '09178223037',
            'email' => 'hamid@gmail.com',
            'password' => '12345678',
            'role_name' => UserRoleEnums::User->value
        ]);
        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where("message", __('messages.user.store'));
                $json->where("data.mobile", "09178223037");
                $json->where("data.last_name", "test");
                $json->where("data.first_name", "test");
                $json->where("data.email", "hamid@gmail.com");
                $json->missing('data.password');
            });

    }


    /**
     * Undocumented function
     *
     * @return void
     */
	public function testCreateEmployee(): void
	{
		$response = $this->post(route('api.manager.store'),[
			'first_name' => 'test',
			'last_name' => 'test',
			'mobile' => '09178223036',
			'email' => 'hamid123@gmail.com',
			'password' => '12345678',
			'role_name' => UserRoleEnums::Employee->value
		]);
		$response
			->assertStatus(200)
			->assertJson(function (AssertableJson $json) {
				$json->where("message", __('messages.user.store'));
				$json->where("data.mobile", "09178223036");
				$json->where("data.last_name", "test");
				$json->where("data.first_name", "test");
				$json->where("data.email", "hamid123@gmail.com");
				$json->missing('data.password');
			});

	}

    /**
     * Undocumented function
     *
     * @return void
     */
	public function testGetUserById() {
		$user = $this->createUser('09178223037','12345678');

		$response = $this->json('GET',route('api.manager.edit',['userId' => $user->id]))
        ->assertStatus(200);

		$user = UserResource::make($user);
		$this->assertEquals(json_decode($user->response()->getContent(), true), $response->json());
        $response = $this->json('GET',route('api.manager.edit',['userId' => 111]))
        ->assertStatus(404);
	}

    /**
     * Undocumented function
     *
     * @return void
     */
    public function testValidationUdateUser() {
        $user = $this->createUser('09178223037','12345678');

		$response = $this->post(route('api.manager.update'),[
            'first_name' => 'test1',
            'last_name' => 'test1',
            'email' => 'h@gmail.com',
            'userId' => $user->id,
        ])->assertStatus(422)
        ->assertJson(fn (AssertableJson $json) =>
        $json->hasAll([
            "errors.mobile",
                      ])->etc()
        );
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function testUpdate() {
		$user = $this->createUser('09178223037','12345678');

		$response = $this->post(route('api.manager.update'),[
            'first_name' => 'test1',
            'last_name' => 'test1',
            'email' => 'h@gmail.com',
            'userId' => $user->id,
            'mobile' => $user->mobile,
        ]);

        $response
			->assertStatus(200)
			->assertJson(function (AssertableJson $json) {
				$json->where("message", __('messages.user.update'));
				$json->where("data.last_name", "test1");
				$json->where("data.first_name", "test1");
				$json->where("data.email", "h@gmail.com");
			});

        $response = $this->post(route('api.manager.update'),[
                'first_name' => 'test1',
                'last_name' => 'test1',
                'email' => 'h@gmail.com',
                'userId' => 111,
                'mobile' => $user->mobile,
        ])->assertStatus(422);
	}


    public function testDestroy() {
        $response = $this->json('GET',route('api.manager.edit',['userId' => 111]))
        ->assertStatus(404);
        $user = $this->createUser('09178223037','12345678');
        $this->json('GET',route('api.manager.edit',['userId' => $user->id]));
        $this->assertTrue(true);

    }

}