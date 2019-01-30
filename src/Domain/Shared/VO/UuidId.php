<?php

/**
 * This file is part of v2.0 PHP library.
 *
 * @author  Anton Karpov <awd.com.ua@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    https://github.com/awd-studio/v2.0
 */

declare(strict_types=1); // strict mode

namespace App\Domain\Shared\VO;

use Assert\Assertion;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidId
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    public function __construct(UuidInterface $uuid)
    {
        Assertion::uuid($uuid);
        $this->uuid = $uuid;
    }

    public static function fromString(string $userId): self
    {
        return new self(Uuid::fromString($userId));
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function equals($other): bool
    {
        return $other instanceof self && $this->uuid->equals($other->uuid);
    }
}
