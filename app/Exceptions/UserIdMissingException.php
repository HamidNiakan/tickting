<?php

namespace App\Exceptions;
use Exception;
use App\Exceptions\Concerns\JsonRender;

class UserIdMissingException extends Exception{
	use JsonRender;
	
	public function __construct(string $message = "Cannot Find Current User Id", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}