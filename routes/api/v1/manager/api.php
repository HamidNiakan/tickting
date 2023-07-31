<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\TicketController;


Route::controller(UserController::class)
	->group(function () {
		Route::get('index','index')->name('index');
		Route::post('store','store')->name('store');
		Route::get('edit','edit')->name('edit');
		Route::post('update','update')->name('update');
        Route::delete('destroy','destroy')->name('destroy');
	});

Route::controller(TicketController::class)
	 ->prefix('ticket')
	 ->name('ticket.')
	 ->group(function () {
		 Route::get('','getAllTicket')->name('get-all-ticket');
		 Route::post('create/ticket','createTicket')->name('create');
		 Route::get('getTicket','findTicket')->name('find');
		 Route::post('create/ticket/replay','createTicketReplay')->name('create.ticket.replay');
	 });

