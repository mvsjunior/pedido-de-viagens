<?php 

namespace App\Domains\Travel\Actions;

use App\Domains\Travel\DTO\TravelRequestDetailDTO;
use App\Domains\Travel\Models\TravelRequest;
use App\Domains\Travel\Models\User;
use App\Domains\Travel\Policies\TravelRequestPolicies;

class ShowTravelRequest 
{
    public function handle(int $userId, int $idTravelRequest):?TravelRequestDetailDTO
    {
        $user = User::find($userId);
        
        $travelRequestModel = TravelRequest::with(['requester:id,name,email']);

        if(!TravelRequestPolicies::canViewAny($user)){
            $travelRequestModel->where('user_id', '=', $user->id);
        }

        $travel = $travelRequestModel->where('id', '=', $idTravelRequest)->first();

        return $travel ?  TravelRequestDetailDTO::createFromModel($travel) : null;
    }
}