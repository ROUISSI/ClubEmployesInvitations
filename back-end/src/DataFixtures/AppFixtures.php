<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadStatusInvitation($manager);
    }

    private function loadStatusInvitation(ObjectManager $manager)
    {
        $status = new Status();
        $status->setLabel("En attente");
        $manager->persist($status);
        $status = new Status();
        $status->setLabel("Acceptée");
        $manager->persist($status);
        $status = new Status();
        $status->setLabel("Réfusée");
        $manager->persist($status);
        $manager->flush();
    }
}
