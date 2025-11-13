<?php 

namespace App\Domains\Travel\DTO;

use App\Domains\Travel\Models\TravelRequest;

class TravelRequestDetailDTO 
{

    public function __construct(
        public readonly int $id,
        public readonly string $requesterName,
        public readonly string $requesterEmail,
        public readonly string $destination,
        public readonly string $departureDate,
        public readonly string $returnDate,
        public readonly string $status,
        public readonly string $requestedOn,
        public readonly string $updatedAt
    ){

    }

    public static function createFromModel(TravelRequest $travelRequest): TravelRequestDetailDTO
    {
        return new TravelRequestDetailDTO(
            $travelRequest->id,
            $travelRequest->requester->name,
            $travelRequest->requester->email,
            $travelRequest->destination,
            $travelRequest->departure_date,
            $travelRequest->return_date,
            $travelRequest->status->name,
            $travelRequest->created_at,
            $travelRequest->updated_at
        );
    }
}