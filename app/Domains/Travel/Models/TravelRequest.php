<?php

namespace App\Domains\Travel\Models;

use App\Domains\Travel\ValueObjects\Status;
use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model {

    protected $table = 'travel_requests';
    protected $fillable = ['user_id', 'destination', 'departure_date', 'return_date'];
    protected $casts = ['status' => Status::class];

    public function requester()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function approve()
    {
        $this->status = Status::Approved;
    }

    public function cancel(int $userId, string $reason)
    {
        $this->canceled_by = $userId;
        $this->cancel_reason = $reason;
        $this->status = Status::Canceled;
    }

    public function isPending(): bool
    {
        $isPending = ($this->status == Status::Pending ? true : false);

        return $isPending;
    }

    public function isCanceled(): bool
    {
        $isCanceled = ($this->status == Status::Canceled ? true : false);

        return $isCanceled;
    }

    public function isApproved(): bool
    {
        $isApproved = $this->status == Status::Approved ? true : false;

        return $isApproved;
    }

    public function reopen(int $userId, string $reason){
        $this->reopened_by = $userId;
        $this->reopen_reason = $reason;
        $this->status = Status::Reopened;
    }
}