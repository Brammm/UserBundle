<?php

namespace Brammm\UserBundle\Tests\EventListener;

use Brammm\UserBundle\EventListener\CanonicalizerSubscriber;

class CanonicalizerSubscriberTest extends \PHPUnit_Framework_TestCase
{
    const EMAIL = 'foo@bar.com';

    /** @var \PHPUnit_Framework_MockObject_MockObject|'\Brammm\UserBundle\Services\Canonicalizer'  */
    private $cm;

    /** @var CanonicalizerSubscriber */
    private $SUT;

    public function setUp()
    {
        $this->cm = $this->getMock('\Brammm\UserBundle\Services\Canonicalizer');

        $this->SUT = new CanonicalizerSubscriber($this->cm);
    }

    public function testListensToCorrectEvents()
    {
        $this->assertEquals(['prePersist', 'preUpdate'], $this->SUT->getSubscribedEvents());
    }

    public function testCanonicalizeViaPrePersist()
    {
        $this->expectCanonicalize();

        $this->SUT->prePersist(
            $this->getEventMock()
        );
    }

    public function testCanonicalizeViaPreUpdate()
    {
        $this->expectCanonicalize();

        $this->SUT->preUpdate(
            $this->getEventMock()
        );
    }

    private function expectCanonicalize()
    {
        $this->cm
            ->expects($this->once())
            ->method('canonicalize')
            ->with($this->equalTo(self::EMAIL));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Doctrine\Common\Persistence\Event\LifecycleEventArgs
     */
    private function getEventMock()
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

        return $event;
    }
} 