<?php

namespace App\DataFixtures;
use App\Entity\Langage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class LangageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $defaultLanguages = ['HTML', 'CSS', 'PHP', 'JS', 'LARAVEL', 'SYMFONY'];

        foreach ($defaultLanguages as $language) {
            $langage = new Langage($language, true);
            $manager->persist($langage);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['langages'];
    }
}
