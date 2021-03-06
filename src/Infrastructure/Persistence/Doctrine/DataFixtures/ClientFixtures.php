<?php

namespace App\Infrastructure\Persistence\Doctrine\DataFixtures;

use App\Domain\Client\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $name = 'Main App';
        $secret = 'secret';
        $client = Client::create($name, $secret);
        $manager->persist($client);

        $manager->flush();
    }
}
