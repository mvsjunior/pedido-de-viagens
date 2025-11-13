<?php 

namespace App\Domains\Travel\Exceptions;

use Exception;

class NotAuthorizedToApprove extends Exception{ 
    public function __construct(string $message = "Not authorized to approve.", int $code = 0, \Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);

    }
}