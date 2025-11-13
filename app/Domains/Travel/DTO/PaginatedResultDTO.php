<?php 

namespace App\Domains\Travel\DTO;

class PaginatedResultDTO 
{
    public function __construct(
        public readonly int $currentPage,
        public readonly int $perPage,
        public readonly int $total,
        public readonly array $data
    ){

    }
}