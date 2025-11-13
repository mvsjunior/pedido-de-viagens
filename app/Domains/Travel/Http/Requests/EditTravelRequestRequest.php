<?php

namespace App\Domains\Travel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Domains\Travel\DTO\CreateTravelRequestDTO;
use App\Domains\Travel\DTO\EditTravelRequestDTO;
use DateTime;

class EditTravelRequestRequest extends FormRequest {

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'destination' => ['string', 'max:200'],
            'departureDate' => ['date', 'after_or_equal:today'],
            'returnDate' => ['date', 'after:departureDate'],
        ];
    }

    public function toDTO(): EditTravelRequestDTO
    {
        return new EditTravelRequestDTO(
            $this->user()->id,
            $this->post('destination',''),
            $this->post('departureDate',''),
            $this->post('returnDate','')
        );
    }
}