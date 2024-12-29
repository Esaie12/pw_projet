<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\JobType;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class JobTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $defaultJobTypes = [
            ['name' => 'Full-time', 'visible' => true],
            ['name' => 'Part-time', 'visible' => true],
            ['name' => 'Contract', 'visible' => true],
            ['name' => 'Internship', 'visible' => true],
            ['name' => 'Freelance', 'visible' => true],
            ['name' => 'Hidden', 'visible' => false],
        ];

        foreach ($defaultJobTypes as $jobTypeData) {
            $jobType = new JobType($jobTypeData['name'], $jobTypeData['visible']);
            $manager->persist($jobType);
        }

        $manager->flush();

    }

    public static function getGroups(): array
    {
        return ['job_types'];
    }
}
