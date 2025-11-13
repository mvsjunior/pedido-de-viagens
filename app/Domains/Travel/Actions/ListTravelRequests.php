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
            $travelRequestModel->where($filter);
        }

        $requests = $travelRequestModel->paginate(5);
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