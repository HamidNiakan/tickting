<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
	$collection = \Illuminate\Support\Facades\DB::table('user_tickets')
		->select('ticket_id', DB::raw('count(*) as total,user_id'))
		  ->groupBy('user_id')->get();
	
	
	$min = $collection->sortBy('total')->first();
	dd($collection,$min);
    return view('welcome');
});
