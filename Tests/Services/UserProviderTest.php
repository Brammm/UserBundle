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
    private $provider;

    public function setUp()
    {
        $this->manager = $this->getMockBuilder('\Brammm\UserBundle\Services\UserManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->provider = new UserProvider($this->manager);
    }

    public function testProvidesAUser()
    {
        $user = new User();

        $this->manager->expects($this->once())
            ->method('findUser')
            ->with($this->equalTo('foo@example.com'))
            ->will($this->returnValue($user));

        $return = $this->provider->loadUserByUsername('foo@example.com');

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

        $this->provider->loadUserByUsername('foo@example.com');
    }

    public function testCanRefreshAUser()
    {
        $user = new User();
        $user->setEmail('foo@example.com');

        $this->manager->expects($this->once())
            ->method('findUser')
            ->with($this->equalTo('foo@example.com'))
            ->will($this->returnValue($user));

        $this->assertEquals($user, $this->provider->refreshUser($user));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testThrowsExceptionWhenItCantRefreshUser()
    {
        $user = new SomeUser();
        $this->provider->refreshUser($user);
    }

    public function testSupportsClass()
    {
        $this->assertTrue($this->provider->supportsClass('Brammm\UserBundle\Entity\User'));
    }

    public function testDoesntSupportClass()
    {
        $this->assertFalse($this->provider->supportsClass('Brammm\UserBundle\Entity\Foo'));
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