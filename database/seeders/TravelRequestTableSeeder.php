<?php

namespace Database\Seeders;

use App\Domains\Travel\Models\TravelRequest;
use Illuminate\Database\Seeder;
use App\Models\User;

class TravelRequestTableSeeder extends Seeder
{
    public function run(): void
    {

        $users = User::all();
        $approverManager = User::where('role', '=', 'manager')->first();

        foreach($users as $requester){
            
            TravelRequest::create([
                'user_id' => $requester->id,
                'destination' => 'São Paulo, SP',
                'departure_date' => now()->addDays(7)->toDateString(),
                'return_date' => now()->addDays(10)->toDateString(),
                'status' => 'pending',
            ]);

            TravelRequest::create([
                'user_id' => $requester->id,
                'destination' => 'São Paulo, SP',
                'departure_date' => now()->addDays(7)->toDateString(),
                'return_date' => now()->addDays(10)->toDateString(),
                'approved_by' => $approverManager->id,
                'status' => 'approved',
            ]);

            TravelRequest::create([
                'user_id' => $requester->id,
                'destination' => 'São Paulo, SP',
                'departure_date' => now()->addDays(7)->toDateString(),
                'return_date' => now()->addDays(10)->toDateString(),
                'canceled_by' => $approverManager->id,
                'cancel_reason' => 'Lorem Ipsum',
                'status' => 'canceled',
            ]);
        }
    }
}
