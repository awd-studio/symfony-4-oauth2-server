<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

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
     * @param string $name
     * @param string $secret
     */
    private function __construct(string $name, string $secret)
    {
        $this->name = $name;
        $this->secret = $secret;
    }

    public static function create(string $name, string $secret): Client
    {
        $client = new self($name, $secret);
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
