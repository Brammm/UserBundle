<?php

namespace Brammm\UserBundle\Tests\EventListener;

use Brammm\UserBundle\EventListener\PasswordSubscriber;

class PasswordSubscriberTest extends \PHPUnit_Framework_TestCase
{
    const PASSWORD = 'foo';

    /** @var \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface */
    private $encoder;
    /** @var \PHPUnit_Framework_MockObject_MockObject|\Doctrine\Common\Persistence\Event\LifecycleEventArgs */
    private $event;
    /** @var \PHPUnit_Framework_MockObject_MockObject|PasswordSubscriber */
    private $SUT;

    public function setUp()
    {
        $this->encoder = $this->getMockBuilder('\Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface')
            ->getMock();

        $this->event = $this->getMockBuilder('\Doctrine\Common\Persistence\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $this->SUT = new PasswordSubscriber($this->encoder);
    }

    public function testListensToCorrectEvents()
    {
        $this->assertEquals(['prePersist', 'preUpdate'], $this->SUT->getSubscribedEvents());
    }

    public function testDoesNothingIfNotAUser()
    {
        $object = new \StdClass();
        $this->eventWillReturnObject($object);

        $this->SUT->prePersist($this->event);
    }

    public function testDoesNothingIfNoPassword()
    {
        $user = $this->getMockBuilder('\Brammm\UserBundle\Entity\User')->getMock();
        $user->expects($this->once())
            ->method('getPlainPassword');

        $this->eventWillReturnObject($user);

        $this->SUT->prePersist($this->event);
    }

    public function testEncodesPassword()
    {
        $user = $this->getMockBuilder('\Brammm\UserBundle\Entity\User')->getMock();
        $user->expects($this->any())
            ->method('getPlainPassword')
            ->will($this->returnValue(self::PASSWORD));
        $user->expects($this->once())
            ->method('setPassword');

        $this->eventWillReturnObject($user);

        $this->ensurePasswordIsEncoded();

        $this->SUT->prePersist($this->event);
    }

    private function ensurePasswordIsEncoded()
    {
        $encoder = $this->getMockBuilder('\Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface')
            ->getMock();
        $encoder->expects($this->once())
            ->method('encodePassword')
            ->with($this->equalTo(self::PASSWORD));

        $this->encoder->expects($this->once())
            ->method('getEncoder')
            ->will($this->returnValue($encoder));
    }

    private function eventWillReturnObject($object)
    {
        $this->event->expects($this->once())
            ->method('getObject')
            ->will($this->returnValue($object));
    }
}