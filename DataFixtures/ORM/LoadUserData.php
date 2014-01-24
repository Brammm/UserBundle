<?php

namespace Brammm\UserBundle\DataFixtures\ORM;

use Brammm\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadUserData implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {

        // Create an admin
        $admin = new User();
        $admin
            ->setFirstName('Bram')
            ->setLastName('Van der Sype')
            ->setEmail('bram.vandersype@gmail.com')
            ->setPassword('test');

        $manager->persist($admin);

        // Generate some users
        $users = 10;

        $faker = Factory::create();

        for($i = 0; $i < $users; ++$i) {
            $user = new User();
            $user
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($faker->word)
                ->setLocked($faker->boolean(25))
                ->setEnabled($faker->boolean(25));

            $manager->persist($user);
        }

        $manager->flush();
    }
}