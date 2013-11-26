<?php

namespace Brammm\UserBundle\Tests\EventListener;

use Brammm\UserBundle\EventListener\LoginFormCreatedListener;
use Symfony\Component\Form\FormError;

class LoginFormCreatedListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider exceptionProvider
     */
    public function testHasErrorMessages($requestException, $sessionException, $message)
    {
        // Mock the Request with an exception or not
        $request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')
            ->enableArgumentCloning()
            ->getMock();
        $paramBag = $this->getMockBuilder('\Symfony\Component\HttpFoundation\ParameterBag')
            ->getMock();
        $paramBag->expects($this->once())
            ->method('has')
            ->will($this->returnValue(($requestException !== null)));
        // If we have a request error, we get it, otherwise we don't
        $howMany = null !== $requestException ? $this->once() : $this->never();
        $paramBag->expects($howMany)
            ->method('get')
            ->will($this->returnValue($requestException));
        $request->attributes = $paramBag;

        // Mock the form
        $form = $this->getMockBuilder('\Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();
        $form->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('login'));
        // If we have an exception, we want to add it to the form
        if (null !== $sessionException || null !== $requestException) {
            $form->expects($this->once())
                ->method('addError')
                ->with($this->equalTo(new FormError($message)));
        } else {
            $form->expects($this->never())
                ->method('addError');
        }

        // Mock event
        $event = $this->getMockBuilder('\Brammm\CommonBundle\Event\FormCreatedEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));
        $event->expects($this->once())
            ->method('getForm')
            ->will($this->returnValue($form));

        // Mock the session, possibly with an exception
        $session = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Session\Session')
            ->getMock();
        $howMany = null === $requestException ? $this->once() : $this->never();
        if (null === $requestException) {
            $session->expects($howMany)
                ->method('remove')
                ->will($this->returnValue($sessionException));
        }

        $listener = new LoginFormCreatedListener($session);

        $listener->onFormCreated($event);
    }

    public function exceptionProvider()
    {
        return [
            [new \Exception('foo'), null, 'foo'],
            [null, new \Exception('bar'), 'bar'],
            [null, null, null],
        ];
    }
}