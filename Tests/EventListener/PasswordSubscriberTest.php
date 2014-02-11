<?php

namespace Brammm\UserBundle\Tests\EventListener;

use Brammm\UserBundle\Entity\User;
use Brammm\UserBundle\EventListener\PasswordSubscriber;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class PasswordSubscriberTest extends \PHPUnit_Framework_TestCase
{
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

    public function testDoesNothingIfNotAUser()
    {
        $object = new Object();
        $this->eventWillReturnObject($object);

        $this->ensureEncoderFactoryIsNotCalled();

        $this->SUT->prePersist($this->event);
    }

    public function testDoesNothingIfNoPassword()
    {
        $object = new User();
        $this->eventWillReturnObject($object);

        $this->ensureEncoderFactoryIsNotCalled();

        $this->SUT->prePersist($this->event);
    }

    public function testEncodesPassword()
    {
        $object = new User();
        $object->setPlainPassword('foo');

        $this->eventWillReturnObject($object);

        $this->ensurePasswordIsEncoded();

        $this->SUT->prePersist($this->event);
    }

    private function ensurePasswordIsEncoded()
    {
        $encoder = new Encoder();
        $this->encoder->expects($this->once())
            ->method('getEncoder')
            ->will($this->returnValue($encoder));
    }

    private function ensureEncoderFactoryIsNotCalled()
    {
        $this->encoder->expects($this->never())
            ->method('getEncoder');
    }

    private function eventWillReturnObject($object)
    {
        $this->event->expects($this->once())
            ->method('getObject')
            ->will($this->returnValue($object));
    }
}


class Object {}

class Encoder implements PasswordEncoderInterface {

    public function encodePassword($raw, $salt)
    {
        return 'foo';
    }

    public function isPasswordValid($encoded, $raw, $salt) {}
}