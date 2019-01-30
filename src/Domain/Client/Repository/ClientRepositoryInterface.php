<?php

namespace App\Domain\Client\Repository;

use App\Domain\Client\Client;

interface ClientRepositoryInterface
{
    public function findActive(string $clientId): ?Client;
}
