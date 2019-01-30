<?php

namespace App\Domain\Client;

use App\Domain\Client\VO\ClientId;
use App\Domain\Shared\VO\UuidId;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class Client
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
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $secret;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $redirect;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    private $active = true;

    /**
     * Client constructor.
     *
     * @param UuidId $clientId
     * @param string $name
     * @param string $secret
     */
    private function __construct(UuidId $clientId, string $name, string $secret)
    {
        $this->id = $clientId->toString();
        $this->name = $name;
        $this->secret = $secret;
    }

    public static function create(string $name, string $secret): Client
    {
        $clientId = ClientId::fromString(Uuid::uuid4()->toString());
        $client = new self($clientId, $name, $secret);
        $redirect = 'test/redirect';
        $client->setRedirect($redirect);

        return $client;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getRedirect(): string
    {
        return $this->redirect;
    }

    /**
     * @param string $redirect
     */
    public function setRedirect(string $redirect): void
    {
        $this->redirect = $redirect;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
