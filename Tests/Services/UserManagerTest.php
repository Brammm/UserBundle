<?php

namespace Brammm\UserBundle\Tests\Services;

use Brammm\UserBundle\Services\UserManager;

class UserManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Brammm\UserBundle\Entity\Repository\UserRepository|\PHPUnit_Framework_MockObject_MockObject */
    private $repo;
    /** @var \Brammm\UserBundle\Services\Canonicalizer|\PHPUnit_Framework_MockObject_MockObject */
    private $canonicalizer;
    /** @var UserManager */
    private $SUT;

    public function setUp()
    {
        $this->repo = $this->getMockBuilder('\Brammm\UserBundle\Entity\Repository\UserRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->canonicalizer = $this->getMockBuilder('\Brammm\UserBundle\Services\Canonicalizer')
            ->getMock();

        $this->SUT = new UserManager($this->repo, $this->canonicalizer);
    }

    public function testFindUser()
    {
        $this->repo->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['emailCanonical' => 'foo']))
            ->will($this->returnValue('bar'));

        $this->canonicalizer->expects($this->once())
            ->method('canonicalize')
            ->will($this->returnValue('foo'));

        $this->assertEquals('bar', $this->SUT->findUser('foo'));
    }
} 