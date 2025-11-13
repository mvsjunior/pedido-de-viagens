<?php

namespace App\Domains\Travel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Domains\Travel\DTO\CreateTravelRequestDTO;
use App\Domains\Travel\ValueObjects\Status;
use DateTime;
use Illuminate\Validation\Rules\Enum;

class ListTravelRequestRequest extends FormRequest {

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['string', 'max:200', new Enum(Status::class)],
            'departureDate' => ['date'],
            'returnDate' => ['date'],
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