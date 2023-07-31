<?php
use App\Http\Controllers\Api\v1\TicketController;
use Illuminate\Support\Facades\Route;
Route::controller(TicketController::class)
	 ->prefix('ticket')
	 ->name('ticket.')
	 ->group(function () {
		 Route::get('','getEmployeeTicket')->name('get-employee-ticket');
		 Route::get('getTicket','findTicket')->name('find');
		 Route::post('create/ticket/replay','createTicketReplay')->name('create.ticket.replay');
	 });
