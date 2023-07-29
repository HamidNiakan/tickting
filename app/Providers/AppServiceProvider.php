<?php

namespace App\Providers;

use App\Repositories\Ticket\TicketRepository;
use App\Repositories\Ticket\TicketRepositoryInterFace;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterFace;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
			UserRepositoryInterFace::class,
			UserRepository::class
		);
		
		$this->app->bind(
			TicketRepositoryInterFace::class,
			TicketRepository::class
		);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
