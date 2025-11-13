<?php

namespace App\Domains\Travel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelTravelRequestRequest extends FormRequest {

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cancelReason' => ['required', 'string', 'min:1','max:250'],
        ];
    }
}