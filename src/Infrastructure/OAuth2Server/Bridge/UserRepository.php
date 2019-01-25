<?php

namespace App\Infrastructure\OAuth2Server\Bridge;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface as AppUserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserRepository implements UserRepositoryInterface
{
    /**
     * @var AppUserRepositoryInterface
     */
    private $appUserRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * UserRepository constructor.
     * @param AppUserRepositoryInterface $appUserRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(
        AppUserRepositoryInterface $appUserRepository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->appUserRepository = $appUserRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface {
        $appUser = $this->appUserRepository->findOneByEmailOrPhone($username);
        if ($appUser === null) {
            return null;
        }

        if (!$this->userPasswordEncoder->isPasswordValid($appUser, $password)) {
            return null;
        }

        return new User($appUser->getId()->toString());
    }
}
