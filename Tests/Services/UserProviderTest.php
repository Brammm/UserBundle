<?php

namespace Brammm\UserBundle\Tests\Services;

use Brammm\UserBundle\Entity\User;
use Brammm\UserBundle\Services\UserProvider;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Brammm\UserBundle\Services\UserManager|\PHPUnit_Framework_MockObject_MockObject */
    private $manager;
    /** @var UserProvider */
    private $SUT;

    public function setUp()
    {
        $this->manager = $this->getMockBuilder('\Brammm\UserBundle\Services\UserManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->SUT = new UserProvider($this->manager);
    }

    public function testProvidesAUser()
    {
        $user = new User();

        $this->manager->expects($this->once())
            ->method('findUser')
            ->with($this->equalTo('foo@example.com'))
            ->will($this->returnValue($user));

        $return = $this->SUT->loadUserByUsername('foo@example.com');

        $this->assertInstanceOf('\Brammm\UserBundle\Entity\User', $return);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testThrowsAnExceptionWhenItCantFindAUser()
    {
        $this->manager->expects($this->once())
            ->method('findUser')
            ->with($this->equalTo('foo@example.com'));

        $this->SUT->loadUserByUsername('foo@example.com');
    }

    public function testCanRefreshAUser()
    {
        $user = new User();
        $user->setEmail('foo@example.com');

        $this->manager->expects($this->once())
            ->method('findUser')
            ->with($this->equalTo('foo@example.com'))
            ->will($this->returnValue($user));

        $this->assertEquals($user, $this->SUT->refreshUser($user));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testThrowsExceptionWhenItCantRefreshUser()
    {
        $user = new SomeUser();
        $this->SUT->refreshUser($user);
    }

    public function testSupportsClass()
    {
        $this->assertTrue($this->SUT->supportsClass('Brammm\UserBundle\Entity\User'));
    }

    public function testDoesntSupportClass()
    {
        $this->assertFalse($this->SUT->supportsClass('Brammm\UserBundle\Entity\Foo'));
    }
}

class SomeUser implements UserInterface
{
    public function getRoles() {}
    public function getPassword() {}
    public function getSalt() {}
    public function getUsername() {}
    public function eraseCredentials() {}
}