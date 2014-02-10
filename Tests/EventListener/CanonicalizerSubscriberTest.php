<?php

namespace Brammm\UserBundle\Tests\EventListener;

use Brammm\UserBundle\Entity\User;
use Brammm\UserBundle\EventListener\CanonicalizerSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class CanonicalizerSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function testCanonicalize()
    {
        $canonicalizerMock = $this->getMockBuilder('\Brammm\UserBundle\Services\Canonicalizer')
            ->getMock();
        $canonicalizerMock
            ->expects($this->once())
            ->method('canonicalize')
            ->with($this->equalTo('foo@bar.com'));

        $user = new User();
        $user->setEmail('foo@bar.com');

        $eventMock = $this->getMockBuilder('\Doctrine\Common\Persistence\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();
        $eventMock
            ->expects($this->once())
            ->method('getObject')
            ->will($this->returnValue($user));

        $subscriber = new CanonicalizerSubscriber($canonicalizerMock);

        $subscriber->prePersist($eventMock);
    }
} 