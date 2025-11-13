<?php 

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTO\TokenInfoDTO;
use App\Domains\Auth\Exceptions\InvalidCredentialsException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthUser
{
    public function handle($email, $password): ?TokenInfoDTO
    {
        $token = JWTAuth::attempt(['email' => $email, 'password' => $password]);

        if(!$token){
            throw new InvalidCredentialsException('Invalid credentials');
        }

        $expiresIn = auth('api')->factory()->getTTL() * 60;
        return new TokenInfoDTO($token,$expiresIn);
    }
}