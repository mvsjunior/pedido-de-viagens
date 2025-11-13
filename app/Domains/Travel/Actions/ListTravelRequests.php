<?php 

namespace App\Domains\Travel\Actions;

use App\Domains\Travel\DTO\PaginatedResultDTO;
use App\Domains\Travel\DTO\TravelRequestDTO;
use App\Domains\Travel\Models\TravelRequest;
use App\Domains\Travel\Models\User;
use App\Domains\Travel\Policies\TravelRequestPolicies;

class ListTravelRequests 
{
    public function handle(User $user, array $filter = []): PaginatedResultDTO
    {
        $travelRequestModel = TravelRequest::with(['requester:id,name']);

        if(!TravelRequestPolicies::canViewAny($user)){
            $filter[] = ['user_id', '=', $user->id];
        }

        if(sizeof($filter)){

            if(isset($filter['departureDate'])){
                $travelRequestModel->where('departure_date', '>=', $filter['departureDate']);
            }

            if(isset($filter['returnDate'])){
                $travelRequestModel->where('return_date', '<=', $filter['returnDate']);
            }

            if(isset($filter['status'])){
                $travelRequestModel->where('status', '=', $filter['status']);
            }
        }

        $requests = $travelRequestModel->paginate(50);
        $dtoRequests = [];

        foreach($requests->items() as $request){
            $dtoRequests[] = TravelRequestDTO::createFromModel($request);
        }

        $paginatedResultDto = new PaginatedResultDTO(
            $requests->currentPage(),
            $requests->perPage(),
            $requests->total(),
            $dtoRequests
        );

        return $paginatedResultDto;
    }
}