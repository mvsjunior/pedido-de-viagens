<?php

namespace App\Domains\Travel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Domains\Travel\DTO\CreateTravelRequestDTO;
use DateTime;

class CreateTravelRequestRequest extends FormRequest {

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'destination' => ['required', 'string', 'max:200'],
            'departureDate' => ['required', 'date', 'after_or_equal:today'],
            'returnDate' => ['required', 'date', 'after:departureDate'],
        ];
    }

    public function toDTO(): CreateTravelRequestDTO
    {
        return new CreateTravelRequestDTO(
            $this->user()->id,
            $this->destination,
            new DateTime($this->departure_date),
            new DateTime($this->return_date)
        );
    }
}