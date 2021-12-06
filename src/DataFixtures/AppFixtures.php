<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $files = [
            __DIR__.'\article.yaml', 
            __DIR__.'\user.yaml', 
            __DIR__.'\category.yaml'
            ];

        $loader = new NativeLoader();
        $entities = $loader->loadFiles($files)->getObjects();

        foreach ($entities as $entity) {
            $manager->persist($entity);
        };

        $manager->flush();
    }
}
