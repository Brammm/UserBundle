<?php

namespace Brammm\UserBundle\DataFixtures\ORM;

use Brammm\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadUserData extends AbstractFixture implements FixtureInterface
{
    const USERS = 10;

    private $counter = 0;

    /** @var \Faker\Generator  */
    private $faker;
    /** @var ObjectManager */
    private $manager;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->createUser(
            'John',
            'Doe',
            'admin@example.com',
            'admin',
            true,
            false
        );

        // Generate some users
        while($this->roomForMore()) {
            $this->createUser();
        }

        $manager->flush();
    }

    /**
     * Creates a user with the given details
     * If details are not provided, Faker data will be used
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $plainPassword
     * @param bool   $enabled
     * @param bool   $locked
     */
    private function createUser(
        $firstName = null,
        $lastName = null,
        $email = null,
        $plainPassword = null,
        $enabled = null,
        $locked = null
    ) {
        $user = new User();
        $user
            ->setFirstName($firstName ?: $this->faker->firstName)
            ->setLastName($lastName ?: $this->faker->lastName)
            ->setEmail($email ?: $this->faker->email)
            ->setPlainPassword($plainPassword ?: $this->faker->word)
            ->setEnabled($enabled ?: $this->faker->boolean(90))
            ->setLocked($locked ?: $this->faker->boolean(20));

        $this->manager->persist($user);

        $this->addReference('user-'.$this->counter, $user);
        $this->counter++;
    }

    /**
     * Counts if there's room for more users
     *
     * @return bool
     */
    private function roomForMore()
    {
        return $this->counter < self::USERS
            ? true
            : false;
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 0; // the order in which fixtures will be loaded
    }
}