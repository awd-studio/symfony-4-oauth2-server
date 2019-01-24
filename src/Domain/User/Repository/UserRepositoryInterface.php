<?php

namespace App\Domain\User\Repository;

use App\Domain\User\User;
use Ramsey\Uuid\UuidInterface;

interface UserRepositoryInterface
{
    public function find(UuidInterface $id): ?User;

    public function findOneByEmailOrPhone(string $username): ?User;

    public function save(User $user): void;

    public function remove(User $user): void;
}
