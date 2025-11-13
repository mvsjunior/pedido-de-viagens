<?php 

namespace App\Domains\Travel\Actions;

use App\Domains\Travel\DTO\EditTravelRequestDTO;
use App\Domains\Travel\DTO\TravelRequestDetailDTO;
use App\Domains\Travel\Exceptions\DepartureDateIsLaterThanReturnDate;
use App\Domains\Travel\Exceptions\UserCannotEditThisRequest;
use App\Domains\Travel\Models\TravelRequest;
use App\Domains\Travel\Models\User;
use App\Domains\Travel\Policies\TravelRequestPolicies;
use DateTime;

class EditTravelRequest 
{
    public function handle(int $userId, EditTravelRequestDTO $dto):?TravelRequestDetailDTO
    {
        $travelRequest = TravelRequest::find($dto->id);

        if(empty($travelRequest)){
            return null;
        }

        $user = User::find($userId);

        if(!TravelRequestPolicies::canEdit($user, $travelRequest)){
            throw new UserCannotEditThisRequest;
        }        

        $travelRequest->departure_date = !empty($dto->departureDate) ? $dto->departureDate : $travelRequest->departure_date;
        $travelRequest->return_date = !empty($dto->returnDate) ? $dto->returnDate : $travelRequest->return_date;
        $travelRequest->destination = !empty($dto->destination) ? $dto->destination : $travelRequest->destination;

        if((new DateTime($travelRequest->return_date) <= new DateTime($travelRequest->departure_date))){
            throw new DepartureDateIsLaterThanReturnDate;
        }

        $travelRequest->save();

        return $travelRequest ?  TravelRequestDetailDTO::createFromModel($travelRequest) : null;
    }
}