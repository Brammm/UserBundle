<?php

namespace Brammm\UserBundle\Tests\Entity;

use Brammm\UserBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testHasARole()
    {
        $user = new User();

        $this->assertNotEmpty($user->getRoles());
    }

    public function testHasDefaultRole()
    {
        $user = new User();

        $this->assertContains(User::DEFAULT_ROLE, $user->getRoles());
    }
} 