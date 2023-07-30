<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;


Route::controller(UserController::class)
	->group(function () {
		Route::get('index','index')->name('index');
		Route::post('store','store')->name('store');
		Route::get('edit','edit')->name('edit');
		Route::post('update','update')->name('update');
        Route::delete('destroy','destroy')->name('destroy');
	});

