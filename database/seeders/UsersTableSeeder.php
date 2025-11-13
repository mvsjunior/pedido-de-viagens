<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'John Requester',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'common_user',
        ]);

        User::create([
            'name' => 'Mary Approver',
            'email' => 'mary@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);
    }
}
