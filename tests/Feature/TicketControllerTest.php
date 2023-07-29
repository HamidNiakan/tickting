<?php

namespace Tests\Feature;

use App\Enums\Ticket\TicketPriorityEnums;
use App\Enums\Ticket\TicketStatusEnums;
use App\Http\Resources\v1\TicketResource;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Concerns\TestingTicket;
use Tests\Feature\Concerns\TestingUser;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
	use DatabaseMigrations;
	use TestingUser;
	use TestingTicket;
	
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
    public function testValidation(): void
    {
		$this->post(route('api.user.create-ticket'),[
			'message' => null,
			'title' => null,
			'priority' => null
		])->assertStatus(422)
			 ->assertJson(fn (AssertableJson $json) =>
			 $json->hasAll([
							   "errors.message",
							   "errors.title",
							   "errors.priority" ,
						   ])->etc()
			 );
    
    }
	
	public function testUserCannotLoginForSendigTicket() {
		$this->post(route('api.user.create-ticket'),[
			'message' => "test",
			'title' => "test",
			'priority' => TicketPriorityEnums::High->value,
			'user_id' => ''
		]) ->assertStatus(400)
			 ->assertJson(array(
				'message' => 'Cannot Find Current User Id'
		));;
	}
	
	public function testCreateTicket() {
		$user = $this->createUser('09178223037','12345678');
		$response = $this->post(route('api.user.create-ticket'),[
			'message' => "test",
			'title' => "test",
			'priority' => TicketPriorityEnums::High->value,
			'user_id' => $user->id
		]);
		$response
			->assertStatus(200)
			->assertJson(function (AssertableJson $json) use($user) {
				$json->where("message", __('messages.ticket.store'));
				$json->where("data.message", "test");
				$json->where("data.title", "test");
				$json->where("data.priority", TicketPriorityEnums::High->value);
				$json->where("data.user_id", $user->id);
				$json->missing('data.password');
			});
			
	}
	
	public function testGetTicketById() {
		$this->json('Get',route('api.user.find-ticket',['ticket_id' => 111]))
		->assertStatus(404);
		$user = $this->createUser('09178223037','12345678');
		$ticket = $this->createTicket($user->id,TicketStatusEnums::Open,TicketPriorityEnums::High);
		$ticket = TicketResource::make($ticket);
		$response = $this->json('Get',route('api.user.find-ticket',['ticket_id' => $ticket->id]));
		$this->assertEquals(json_decode($ticket->response()->getContent(), true), $response->json());
	}
	
	
	public function testValidationCreateTicketReplay () {
		$this->post(route('api.user.create.ticket.replay'),[
			'message' => null,
			'user_id'=> null,
			'ticket_id' => null
		])->assertStatus(422)
			 ->assertJson(fn (AssertableJson $json) =>
			 $json->hasAll([
							   "errors.message",
							   "errors.ticket_id" ,
						   ])->etc()
			 );
	}
	
	public function testCreateTicketReplayWhenUserCannotLogin () {
		$user = $this->createUser('09178223037','12345678');
		$ticket = $this->createTicket($user->id,TicketStatusEnums::Open,TicketPriorityEnums::High);
		$this->post(route('api.user.create.ticket.replay'),[
			'message' => fake()->name(),
			'user_id'=> null,
			'ticket_id' => $ticket->id
		]) ->assertStatus(400)
			 ->assertJson(array(
							  'message' => 'Cannot Find Current User Id'
						  ));
			
	}
	
	public function testCreateTicketReplayWhenTicketIsClosed () {
		$user = $this->createUser('09178223037','12345678');
		$ticket = $this->createTicket($user->id,TicketStatusEnums::Closed,TicketPriorityEnums::High);
		$this->post(route('api.user.create.ticket.replay'),[
			'message' => fake()->name(),
			'user_id'=> $user->id,
			'ticket_id' => $ticket->id
		]) ->assertStatus(400)
			 ->assertJson(array(
							  'message' => 'the ticket closed'
						  ));
		
	}
	
	public function testCreateTicketReplay() {
		$user = $this->createUser('09178223037','12345678');
		$ticket = $this->createTicket($user->id,TicketStatusEnums::Open,TicketPriorityEnums::High);
		$response = $this->post(route('api.user.create.ticket.replay'),[
			'message' => fake()->name(),
			'user_id'=> $user->id,
			'ticket_id' => $ticket->id
		]);
		
		$response
			->assertStatus(200)
			->assertJson(function (AssertableJson $json) use($user,$ticket) {
				$json->where("message", __('messages.ticket.store_ticket_replay'));
				$json->where("data.message", $ticket->message);
				$json->where("data.title", $ticket->title);
				$json->where("data.priority", $ticket->priority->value);
				$json->where("data.user_id", $ticket->user_id);
				$json->missing('data.password');
			});
		$this->assertTicketReplay($response['data']['ticketReplies'],$ticket->id);
	}
	
	protected function assertTicketReplay(array $array,$id) {
		$found = false;
		foreach ($array  as $item) {
			if ($item['ticket_id'] == $id) {
				$found = true;
				break;
			}
		}
		$this->assertTrue($found);
	}
}