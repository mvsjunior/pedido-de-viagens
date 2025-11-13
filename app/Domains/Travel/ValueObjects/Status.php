<?php 

namespace App\Domains\Travel\ValueObjects;

enum Status: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Canceled = 'canceled';
    case Reopened = 'reopened';
} 