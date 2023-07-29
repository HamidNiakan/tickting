<?php

namespace App\Repositories\Ticket;
use App\Enums\Ticket\TicketStatusEnums;
use App\Exceptions\AlreadyTicketByClosedException;
use App\Exceptions\InvalidStatusTicketException;
use App\Exceptions\UserIdMissingException;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketRepository implements TicketRepositoryInterFace {
	public function index (): Collection {
		// TODO: Implement index() method.
	}
	
	public function createTicket ( array $data, int $userId ): Model {
		$ticket = new Ticket();
		$ticket->fill($data);
		$ticket->user_id = $userId;
		$ticket->status = TicketStatusEnums::Open;
		$ticket->is_read = 0;
		$ticket->save();
		return  $ticket;
	}
	
	public function getTicketById (int $ticketId , array $relationships = [] ):Model|ModelNotFoundException {
		$query = $this->getQuery();
		
		if (is_array($relationships)) {
			$query = $query->with($relationships);
		}
		return $query->findOrFail($ticketId);
	}
	
	public function createTicketReplay ( string $message,int $userId , int $ticketId ): Model {
		$ticket = $this->getTicketById($ticketId);
		
		if ($userId == null) {
			throw new UserIdMissingException();
		}
		
		if ($ticket->status ==  TicketStatusEnums::Closed) {
			throw new InvalidStatusTicketException();
		}
		$ticket->ticketReplies()
			->create([
				'user_id' => $userId,
				'message' => $message
			]);
		$ticket->update([
							'is_read' => $ticket->user_id == $userId ? 0 : 1,
		]);
		return  $ticket->refresh();
	}
	
	public function closedTicket (int|string $ticketId ): model {
		$ticket = $this->getTicketById($ticketId);
		if ($ticket->status ==  TicketStatusEnums::Closed) {
			throw new AlreadyTicketByClosedException();
		}
		
		$ticket->update([
			'status' => TicketStatusEnums::Closed
		]);
		return $ticket->refresh();
	}
	
	public function readTicket ( bool $is_read , int $ticketId ): Model {
		$ticket = $this->getTicketById($ticketId);
		if ($ticket->status ==  TicketStatusEnums::Closed) {
			throw new InvalidStatusTicketException();
		}
		
		$ticket->update([
			'is_read' => 1
		]);
		
		return $ticket->refresh();
	}
	
	
	protected function getQuery() {
		return Ticket::query();
	}
}