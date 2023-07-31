<?php

namespace Tests\Feature\Concerns;
use App\Enums\Ticket\TicketPriorityEnums;
use App\Enums\Ticket\TicketStatusEnums;
use App\Models\Ticket;

trait TestingTicket {
	public function createTicket(int $userId,TicketStatusEnums $status,TicketPriorityEnums $priority) {
		return Ticket::factory()
			->setUser($userId)
			->setStatus($status)
			->setPriority($priority)
			->create();
	}
	public function createTickets(int $userId,TicketStatusEnums $status,TicketPriorityEnums $priority,int $count = 1) {
		return Ticket::factory()
					 ->setUser($userId)
					 ->setStatus($status)
					 ->setPriority($priority)
					->count($count)
					 ->create();
	}
}