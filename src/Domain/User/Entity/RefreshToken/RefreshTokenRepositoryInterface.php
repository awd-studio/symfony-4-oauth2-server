<?php

namespace App\Domain\User\Entity\RefreshToken;

interface RefreshTokenRepositoryInterface
{
    public function find(string $refreshTokenId): ?RefreshToken;

    public function save(RefreshToken $refreshToken): void;
}
