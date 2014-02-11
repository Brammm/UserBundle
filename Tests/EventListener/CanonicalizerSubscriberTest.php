<?php

namespace Brammm\UserBundle\Tests\EventListener;

use Brammm\UserBundle\EventListener\CanonicalizerSubscriber;

class CanonicalizerSubscriberTest extends \PHPUnit_Framework_TestCase
{
    const EMAIL = 'foo@bar.com';

    /** @var CanonicalizerSubscriber */
    private $SUT;

    public function setUp()
    {
        $canonicalizerMock = $this->getMockBuilder('\Brammm\UserBundle\Services\Canonicalizer')
            ->getMock();
        $canonicalizerMock
            ->expects($this->once())
            ->method('canonicalize')
            ->with($this->equalTo(self::EMAIL));

        $this->SUT = new CanonicalizerSubscriber($canonicalizerMock);
    }

    public function testCanonicalize()
    {
        $event = $this->getMockBuilder('\Doctrine\Common\Persistence\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $user = $this->getMockBuilder('\Brammm\UserBundle\Entity\User')
            ->getMock();
        $user->expects($this->once())
            ->method('getEmail')
            ->will($this->returnValue(self::EMAIL));

        $event
            ->expects($this->once())
            ->method('getObject')
            ->will($this->returnValue($user));

        $this->SUT->prePersist($event);
    }
} 