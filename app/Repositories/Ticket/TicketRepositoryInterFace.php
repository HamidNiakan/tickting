<?php

namespace App\Repositories\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface TicketRepositoryInterFace {
	
	public function index():Collection;
	
	public function createTicket(array $data,int $userId):Model;
	
	public function getTicketById(int $ticketId,array $relationships):Model|ModelNotFoundException;
	
	public function createTicketReplay(string $message,int $userId ,int $ticketId):Model;
	
	public function closedTicket(int $ticketId):model;
	
	public function readTicket(bool $is_read,int $ticketId):Model;
}