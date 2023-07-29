<?php

namespace App\Exceptions;
use App\Exceptions\Concerns\JsonRender;
use Exception;
class AlreadyTicketByClosedException extends Exception {
	use JsonRender;
	
	public function __construct(string $message = "Already ticket by closed", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}