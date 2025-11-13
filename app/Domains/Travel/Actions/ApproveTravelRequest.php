<?php 

namespace App\Domains\Travel\Actions;

use App\Domains\Travel\Exceptions\NotAuthorizedToApprove;
use App\Domains\Travel\Exceptions\TravelHasAlreadyBeenApproved;
use App\Domains\Travel\Exceptions\TravelRequestNotFound;
use App\Domains\Travel\Exceptions\UserNotFound;
use App\Domains\Travel\Models\TravelRequest;
use App\Domains\Travel\Models\User;
use App\Domains\Travel\Policies\TravelRequestPolicies;

class ApproveTravelRequest 
{
    public function handle(int $userId, int $travelRequestId)
    {
        $travelRequest = TravelRequest::find($travelRequestId);

        $approver = User::find($userId);

        if(empty($travelRequest)){
            throw new TravelRequestNotFound;
        }

        if(empty($approver)){
            throw new UserNotFound;
        }

        if($travelRequest->isApproved()){
            throw new TravelHasAlreadyBeenApproved;
        }

        if(!TravelRequestPolicies::canApprove($approver, $travelRequest)){
            throw new NotAuthorizedToApprove;
        }

        $travelRequest->approve();
        $travelRequest->save();
    }
}