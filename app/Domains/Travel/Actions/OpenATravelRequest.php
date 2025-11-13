<?php

namespace App\Domains\Travel\Actions;

use App\Domains\Travel\DTO\CreateTravelRequestDTO;
use App\Domains\Travel\Models\TravelRequest;

class OpenATravelRequest 
{
    public function handle(CreateTravelRequestDTO $dto): void
    {
        TravelRequest::create([
            "user_id" => $dto->requesterId,
            "destination" => $dto->destination,
            "departure_date" => $dto->departureDate,
            "return_date" => $dto->returnDate
        ]);
    }
}