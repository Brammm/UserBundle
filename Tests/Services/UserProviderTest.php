<?php

namespace Brammm\UserBundle\Tests\Services;

use Brammm\UserBundle\Security\UserProvider;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Brammm\UserBundle\Security\UserRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $repo;
    /** @var UserProvider */
    private $SUT;

    public function setUp()
    {
        $this->repo = $this->getMockBuilder('Brammm\UserBundle\Security\UserRepositoryInterface')
            ->getMock();

        $this->SUT = new UserProvider($this->repo);
    }

    public function testProvidesAUser()
    {
        $userInterface = $this->getMockBuilder('Brammm\UserBundle\Model\SimpleUserInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->repo->expects($this->once())
            ->method('findOneByUsername')
            ->with($this->equalTo('foo@example.com'))
            ->will($this->returnValue($userInterface));

        $this->assertInstanceOf(
            'Brammm\UserBundle\Model\User',
            $this->SUT->loadUserByUsername('foo@example.com')
        );
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testThrowsAnExceptionWhenItCantFindAUser()
    {
        $this->repo->expects($this->once())
            ->method('findOneByUsername')
            ->with($this->equalTo('foo@example.com'));

        $this->SUT->loadUserByUsername('foo@example.com');
    }

    public function testCanRefreshAUser()
    {
        $user = $this->getMockBuilder('Brammm\UserBundle\Model\User')
            ->disableOriginalConstructor()
            ->getMock();
        $user->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('foo'));

        $userEntity = $this->getMockBuilder('Brammm\UserBundle\Model\SimpleUserInterface')
            ->getMock();
        $this->repo->expects($this->once())
            ->method('findOneByUsername')
            ->will($this->returnValue($userEntity));

        $this->assertInstanceOf(
            'Brammm\UserBundle\Model\User',
            $this->SUT->refreshUser($user)
        );
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