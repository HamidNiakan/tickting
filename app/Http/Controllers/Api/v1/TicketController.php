<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\UserRoleEnums;
use App\Events\AssginTicketEvent;
use App\Exceptions\UserIdMissingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Ticket\CreateRequest;
use App\Http\Requests\Api\v1\Ticket\CreateTicketReplayRequest;
use App\Http\Requests\Api\v1\Ticket\TicketRequest;
use App\Http\Resources\v1\TicketResource;
use App\Jobs\SendEmailJob;
use App\Jobs\SendSms;
use App\Mail\SendEmail;
use App\Models\User;
use App\Notifications\TicketAnswered;
use App\Repositories\Ticket\TicketRepositoryInterFace;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use function App\Helper\printResult;

class TicketController extends Controller
{
   public function __construct (public TicketRepositoryInterFace $repository) {

   }

   public function index() {

   }

   public function createTicket(CreateRequest  $request) {
	  $data = $request->toArray();
	  $user = $this->getAuthUser();
	   if ($user == null) {
		   throw new UserIdMissingException();
	   }
	  $ticket = $this->repository->createTicket($data,(int)$user->id);
	   event(new AssginTicketEvent($ticket));
	   $ticket = TicketResource::make($ticket);
	  return printResult($ticket,__('messages.ticket.store'));
   }


   public function findTicket(TicketRequest $request) {

	   try {
		   $ticketId = (int)$request->get('ticket_id');
		   $ticket = $this->repository->getTicketById($ticketId,['user','ticketReplies']);
		   $ticket = TicketResource::make($ticket);
		   return response()->json(['data' => $ticket]);
	   } catch (ModelNotFoundException $exception) {
		   $messages = __('messages.global.error');
		   return printResult([],$messages,404);
	   }
   }

   public function createTicketReplay(CreateTicketReplayRequest $request) {
	   try {
		   $messages = $request->get('message');
		   $userId = (int)$request->get('user_id');
		   $ticketId = (int)$request->get('ticket_id');
		   $ticket = $this->repository->createTicketReplay($messages,$userId,$ticketId);



		   if ($ticket->user) {
			   SendSms::dispatch($ticket->user,__('messages.answer-ticket.message'));
		   }

		   if ($userId) {
			   $user = $this->getUserById($userId);
               $email = new SendEmail($user);
			   SendEmailJob::dispatch($user);
		   }

		   $adminUser = $this->getAdminUser();
        
		   if ($adminUser) {
			   $data = [
				   'title' => $ticket->title,
				   'ticket_id' => $ticket->id
			   ];
			   Notification::send($user, new TicketAnswered($data));
		   }

           $ticket = TicketResource::make($ticket->load('user','ticketReplies'));
		   return printResult($ticket,__('messages.ticket.store_ticket_replay'));
	   } catch (ModelNotFoundException $exception) {
		   $messages = __('messages.global.error');
		   return printResult([],$messages,404);
	   }
   }

	public function closedTicket(TicketRequest $request) {
		try {
			$ticket = $this->repository->closedTicket((int)$request->get('ticket_id'));
			$ticket = TicketResource::make($ticket->load('user','ticketReplies'));
			return printResult($ticket);
		} catch (ModelNotFoundException $exception) {
			$messages = __('messages.global.error');
			return printResult([],$messages,404);
		}
	}
   /*
    *
    */
   protected function getAuthUser() {
	   return User::query()
		   ->role(UserRoleEnums::User->value)
		   ->first();
   }

	protected function getAdminUser() {
		return User::query()
				   ->role(UserRoleEnums::User->value)
				   ->first();
	}

   protected function getUserById(int $userId) {
	   return User::query()->find($userId);
   }


}
