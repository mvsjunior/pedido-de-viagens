<?php

namespace App\Domains\Travel\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = "users";

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function travelRequests(){
        return $this->hasMany(TravelRequest::class);
    }

    public function isManager(){
        return $this->role == 'manager';
    }

    public function isAdmin(){
        return $this->role == 'admin';
    }
}