<?php

namespace App\Domain\User\Entity\RefreshToken;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class RefreshToken
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid_binary_ordered_time", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $accessTokenId;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $revoked = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    /**
     * RefreshToken constructor.
     *
     * @param string    $id
     * @param string    $accessTokenId
     * @param \DateTime $expiresAt
     */
    public function __construct(string $id, string $accessTokenId, \DateTime $expiresAt)
    {
        $this->id = $id;
        $this->accessTokenId = $accessTokenId;
        $this->expiresAt = $expiresAt;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAccessTokenId(): string
    {
        return $this->accessTokenId;
    }

    /**
     * @return bool
     */
    public function isRevoked(): bool
    {
        return $this->revoked;
    }

    public function revoke(): void
    {
        $this->revoked = true;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt(): \DateTime
    {
        return $this->expiresAt;
    }
}
