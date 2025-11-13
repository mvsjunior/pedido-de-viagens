<?php

namespace App\Domains\Travel\DTO;

use App\Domains\Travel\Http\Requests\EditTravelRequestRequest;

class EditTravelRequestDTO {

    public function __construct(
        public readonly int $id,
        public readonly string $destination,
        public readonly string $departureDate,
        public readonly string $returnDate
    ){}

    public static function createFromRequest(EditTravelRequestRequest $request){

        return new EditTravelRequestDTO(
            (int) $request->id,
            $request->post('destination',''),
            $request->post('departureDate',''),
            $request->post('returnDate','')
        );
    }
}