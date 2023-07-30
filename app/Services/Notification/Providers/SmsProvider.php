<?php

namespace App\Services\Notification\Providers;
use App\Models\User;
use App\Services\Notification\Providers\Contracts\Provider;
use Illuminate\Support\Facades\Log;

class SmsProvider implements Provider{
	
	
	public function __construct (public User $user, public String $message) { }
	
	public function send () {
		// TODO: Implement send() method.
		Log::info('send sms to user');
	}
}