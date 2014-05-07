<?php

namespace Brammm\UserBundle\Tests\Model;

use Brammm\UserBundle\Model\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Brammm\UserBundle\Model\SimpleUserInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $userInterface;
    /** @var User */
    protected $SUT;

    public function setUp()
    {
        $this->userInterface = $this->getMockBuilder('Brammm\UserBundle\Model\SimpleUserInterface')
            ->getMock();

        $this->userInterface->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('foo'));
        $this->userInterface->expects($this->once())
            ->method('getPassword')
            ->will($this->returnValue('bar'));

        $this->SUT = new User($this->userInterface);
    }

    public function testHasUsername()
    {
        $this->assertEquals('foo', $this->SUT->getUsername());
    }

    public function testHasPassword()
    {
        $this->assertEquals('bar', $this->SUT->getPassword());
    }

    public function testHasARole()
    {
        $this->assertNotEmpty($this->SUT->getRoles());
    }

    public function testHasDefaultRole()
    {
        $this->assertContains(User::DEFAULT_ROLE, $this->SUT->getRoles());
    }
} 