<?php

namespace Database\Factories\Domains\Travel\Models;

use App\Domains\Travel\Models\TravelRequest;
use App\Domains\Travel\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelRequestFactory extends Factory
{
    protected $model = TravelRequest::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'destination' => $this->faker->city(),
            'departure_date' => now()->addDays(5)->toDateString(),
            'return_date' => now()->addDays(10)->toDateString(),
            'status' => 'pending',
        ];
    }
}
