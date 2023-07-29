<?php

namespace App\Exceptions;
use Exception;
use App\Exceptions\Concerns\JsonRender;
class InvalidStatusTicketException extends Exception {
	use JsonRender;
	
	public function __construct(string $message = "the ticket closed", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}