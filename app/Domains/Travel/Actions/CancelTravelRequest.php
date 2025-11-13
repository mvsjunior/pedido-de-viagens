<?php 

namespace App\Domains\Travel\Actions;

use App\Domains\Travel\Exceptions\NotAuthorizedToCancel;
use App\Domains\Travel\Exceptions\TravelHasAlreadyBeenCanceled;
use App\Domains\Travel\Exceptions\TravelRequestNotFound;
use App\Domains\Travel\Exceptions\UserNotFound;
use App\Domains\Travel\Models\TravelRequest;
use App\Domains\Travel\Models\User;
use App\Domains\Travel\Policies\TravelRequestPolicies;
use App\Mail\TravelRequestCanceledMail;
use Illuminate\Support\Facades\Mail;

class CancelTravelRequest 
{
    public function handle(int $userId, int $travelRequestId, string $reason)
    {
        $travelRequest = TravelRequest::find($travelRequestId);
        $approver = User::find($userId);

        if(empty($travelRequest)){
            throw new TravelRequestNotFound;
        }

        if(empty($approver)){
            throw new UserNotFound;
        }

        if($travelRequest->isCanceled()){
            throw new TravelHasAlreadyBeenCanceled;
        }

        if(!TravelRequestPolicies::canCancel($approver, $travelRequest)){
            throw new NotAuthorizedToCancel;
        }

        $travelRequest->cancel($userId, $reason);
        $travelRequest->save();

        // Enviar e-mail
        Mail::to($travelRequest->requester->email)->send(new TravelRequestCanceledMail($travelRequest));

    }
}