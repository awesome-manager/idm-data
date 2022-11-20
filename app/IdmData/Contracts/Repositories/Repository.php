<?php

namespace App\IdmData\Contracts\Repositories;

interface Repository
{
    public function user(): UserRepository;
}
