<?php

namespace App\Domains\Travel\Models;

use App\Models\User as ModelsUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends ModelsUser
{
    protected $table = "users";

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function travelRequests(){
        return $this->belongSto(TravelRequest::class);
    }

    public function isManager(){
        return $this->role == 'manager';
    }

    public function isAdmin(){
        return $this->role == 'admin';
    }
}