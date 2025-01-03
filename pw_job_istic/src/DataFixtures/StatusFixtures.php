<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Status;

class StatusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $statuses = [
            ['name' => 'en attente', 'color' => 'orange'],
            ['name' => 'accepté', 'color' => 'green'],
            ['name' => 'rejeté', 'color' => 'red'],
            ['name' => 'Poste pourvu', 'color' => 'red'],
        ];

        foreach ($statuses as $data) {
            $status = new Status();
            $status->setName($data['name']);
            $status->setColor($data['color']);
            $manager->persist($status);
        }

        $manager->flush();
    }
}
