<?php 

namespace App\Domains\Travel\DTO;

use App\Domains\Travel\Models\TravelRequest;

class TravelRequestDTO 
{

    public function __construct(
        public readonly int $id,
        public readonly string $requesterName,
        public readonly string $destination,
        public readonly string $status,
        public readonly string $departureDate,
        public readonly string $returnDate
    ){

    }

    public static function createFromModel(TravelRequest $travelRequest): TravelRequestDTO
    {
        return new TravelRequestDTO(
            $travelRequest->id,
            $travelRequest->requester->name,
            $travelRequest->destination,
            $travelRequest->status->name,
            $travelRequest->departure_date,
            $travelRequest->return_date
        );
    }
}