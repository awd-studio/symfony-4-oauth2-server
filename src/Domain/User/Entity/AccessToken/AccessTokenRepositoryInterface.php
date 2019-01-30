<?php

namespace App\Domain\User\Entity\AccessToken;

interface AccessTokenRepositoryInterface
{
    public function find(string $accessTokenId): ?AccessToken;

    public function save(AccessToken $accessToken): void;
}
