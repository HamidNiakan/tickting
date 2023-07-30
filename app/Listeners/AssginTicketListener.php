<?php

namespace App\Listeners;

use App\Enums\UserRoleEnums;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class AssginTicketListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
		$ticket = $event->ticket;
		$employeeses = User::query()
								->role(UserRoleEnums::Employee->value)
								->doesnthave('assginTickets')
								->get();
		
		if ($employeeses->isNotEmpty()) {
			$employee = $employeeses->random();
			$employee->assginTickets()->attach($ticket->id);
		}
		
		if ($employeeses->isEmpty()) {
			$collection = DB::table('user_tickets')
			->select('ticket_id', DB::raw('count(*) as total,user_id'))
			->groupBy('user_id')->get();
			$item = $collection->sortBy('total')->first();
			if ($item) {
				$user = User::query()
							->find($item->user_id);
				$user->assginTickets()->attach($ticket->id);
			}
			
		}
    }
}
