<?php

namespace App\Domains\Travel\Models;

use App\Domains\Travel\ValueObjects\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Travel\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TravelRequest extends Model {

    use HasFactory;

    protected $table = 'travel_requests';
    protected $fillable = ['user_id', 'destination', 'departure_date', 'return_date'];
    protected $casts = ['status' => Status::class];

    public function requester():HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function approve(int $userId)
    {
        $this->approved_by = $userId;
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