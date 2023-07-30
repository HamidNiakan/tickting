<?php

namespace App\Services\Notification;

use App\Models\User;
use App\Services\Notification\Providers\Contracts\Provider;
use Illuminate\Mail\Mailable;

/**
 * @method mixed sendEmail(User $user, Mailable $mailable)
 * @method mixed sendSms(User $user, String $message)
 */

class Notification {
	public function __call($method, $args)
	{
		$providerPath = __NAMESPACE__ . '\Providers\\' . substr($method, 4) . 'Provider';
		
		if (!class_exists($providerPath)) {
			throw new \Exception("Class does not exist");
		}
		
		$providerInstance = new $providerPath(...$args);
		
		if (!is_subclass_of($providerInstance, Provider::class)) {
			throw new \Exception('class must be implements App\Services\Notification\Providers\Contracts\Provider');
		}
		
		$providerInstance->send();
	}
	
}