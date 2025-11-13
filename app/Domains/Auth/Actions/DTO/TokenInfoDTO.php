<?php 

namespace App\Domains\Auth\DTO;

class TokenInfoDTO {
    public function __construct(
        public readonly string $token,
        public readonly int $expiresIn
    ){}
}