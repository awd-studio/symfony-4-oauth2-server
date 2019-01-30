<?php

namespace App\Infrastructure\OAuth2Server\Bridge;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use App\Domain\User\Entity\RefreshToken\RefreshTokenRepositoryInterface as AppRefreshTokenRepositoryInterface;
use App\Domain\User\Entity\RefreshToken\RefreshToken as AppRefreshToken;

final class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /** @var AppRefreshTokenRepositoryInterface */
    private $appRefreshTokenRepository;

    /**
     * RefreshTokenRepository constructor.
     * @param AppRefreshTokenRepositoryInterface $appRefreshTokenRepository
     */
    public function __construct(AppRefreshTokenRepositoryInterface $appRefreshTokenRepository)
    {
        $this->appRefreshTokenRepository = $appRefreshTokenRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken(): RefreshTokenEntityInterface
    {
        return new RefreshToken();
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        $id = $refreshTokenEntity->getIdentifier();
        $accessTokenId = $refreshTokenEntity->getAccessToken()->getIdentifier();
        $expiryDateTime = $refreshTokenEntity->getExpiryDateTime();

        $refreshTokenPersistEntity = new AppRefreshToken($id, $accessTokenId, $expiryDateTime);
        $this->appRefreshTokenRepository->save($refreshTokenPersistEntity);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId): void
    {
        $refreshTokenPersistEntity = $this->appRefreshTokenRepository->find($tokenId);
        if ($refreshTokenPersistEntity === null) {
            return;
        }
        $refreshTokenPersistEntity->revoke();
        $this->appRefreshTokenRepository->save($refreshTokenPersistEntity);
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId): bool
    {
        $refreshTokenPersistEntity = $this->appRefreshTokenRepository->find($tokenId);
        if ($refreshTokenPersistEntity === null || $refreshTokenPersistEntity->isRevoked()) {
            return true;
        }
        return $this->accessTokenRepository->isAccessTokenRevoked($refreshTokenPersistEntity->getAccessTokenId());
    }
}
