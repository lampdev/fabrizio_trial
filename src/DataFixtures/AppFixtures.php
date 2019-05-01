<?php

namespace App\DataFixtures;

use AppBundle\Entity\Donators;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {
            $donators = new Donators();
            $donators->setName('donator_ '.$i);
            $donators->setEmail('donator '.$i.'@mail.email');
            $donators->setAmount(mt_rand(10, 100) / 10);
            $donators->setMessage('donators_message_number_ '.$i);
            $donators->setCreatedAt(
                date_create_from_format('Y-m-d h:i:s', date('Y-m-d h:i:s', strtotime('-' . $i . ' days')))
            );

            $manager->persist($donators);
        }

        $manager->flush();
    }
}