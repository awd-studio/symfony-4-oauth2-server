<?php

namespace App\Application\Repository\Doctrine;

use App\Domain\User\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class UserRepository implements UserRepositoryInterface
{
    private const ENTITY = User::class;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $objectRepository;

    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(self::ENTITY);
    }

    public function find(UuidInterface $id): ?User
    {
        return $this->entityManager->find(self::ENTITY, $id->toString());
    }

    public function findOneByEmailOrPhone(string $username): ?User
    {
        return $this->objectRepository->findOneBy(['email' => $username]);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function remove(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
