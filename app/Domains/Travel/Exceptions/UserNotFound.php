<?php 

namespace App\Domains\Travel\Exceptions;

use Exception;
use Throwable;

class UserNotFound extends Exception
{
    public function __construct(string $message = "User not found.", int $code = 0, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}