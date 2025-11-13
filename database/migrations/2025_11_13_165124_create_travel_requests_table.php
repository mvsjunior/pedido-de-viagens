<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                    ->constrained('users')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            $table->string('destination');
            $table->date('departure_date');
            $table->date('return_date');
            $table->enum('status', ['pending', 'approved', 'canceled'])->default('pending');
            $table->foreignId('canceled_by')
                    ->nullable()
                    ->constrained('users')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            $table->foreignId('approved_by')
                    ->nullable()
                    ->constrained('users')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            $table->string('cancel_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_requests');
    }
};
