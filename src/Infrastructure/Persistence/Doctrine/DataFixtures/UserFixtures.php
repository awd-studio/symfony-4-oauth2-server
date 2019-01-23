<?php

namespace App\Infrastructure\Persistence\Doctrine\DataFixtures;

use App\Domain\Model\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $users = ['user'];

        foreach ($users as $name) {
            $email = "{$name}@test.mail";
            $user = User::create($email);
            $password = $this->encoder->encodePassword($user, $name);
            $user->setPassword($password);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
