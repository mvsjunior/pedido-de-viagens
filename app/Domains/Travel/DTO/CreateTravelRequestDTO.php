<?php

namespace App\Domains\Travel\DTO;

class CreateTravelRequestDTO {

    public function __construct(
        public readonly int $requesterId,
        public readonly string $destination,
        public readonly \DateTime $departureDate,
        public readonly \DateTime $returnDate
    ){}
}