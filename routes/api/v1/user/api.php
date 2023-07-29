<?php
use App\Http\Controllers\Api\v1\TicketController;
use Illuminate\Support\Facades\Route;


Route::controller(TicketController::class)
	->group(function () {
		Route::get('index','index')->name('index');
		Route::post('create/ticket','createTicket')->name('create-ticket');
		Route::get('getTicket','findTicket')->name('find-ticket');
		Route::post('create/ticket/replay','createTicketReplay')->name('create.ticket.replay');
	});