<?php
use App\Http\Controllers\Api\v1\TicketController;
use Illuminate\Support\Facades\Route;


Route::controller(TicketController::class)
	->prefix('ticket')
	->name('ticket.')
	->group(function () {
		Route::get('','getUserTickets')->name('get-user-tickets');
		Route::post('create/ticket','createTicket')->name('create');
		Route::get('getTicket','findTicket')->name('find');
		Route::post('create/ticket/replay','createTicketReplay')->name('create.ticket.replay');
	});