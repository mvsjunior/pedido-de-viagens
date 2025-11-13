<?php 

namespace App\Domains\Travel\Exceptions;

use Exception;
use Throwable;

class TravelHasAlreadyBeenCanceled extends Exception
{
    public function __construct(string $message = "The travel request has already been canceled.", int $code = 0, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}