<?php 

namespace App\Domains\Travel\Exceptions;

use Exception;

class NotAuthorizedToCancel extends Exception{ 
    public function __construct(string $message = "Not authorized to approve.", int $code = 0, \Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);

    }
}