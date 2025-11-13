<?php 

namespace App\Domains\Travel\Exceptions;

use Exception;
use Throwable;

class TravelRequestNotFound extends Exception
{
    public function __construct(string $message = "Travel Request not found.", int $code = 0, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}