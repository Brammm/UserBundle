<?php

namespace Brammm\UserBundle\Tests\EventListener;

use Brammm\UserBundle\EventListener\LoginFormCreatedListener;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\SecurityContext;

class LoginFormCreatedListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Form\Form */
    private $form;
    /** @var \PHPUnit_Framework_MockObject_MockObject|\Brammm\CommonBundle\Event\FormCreatedEvent */
    private $event;
    /** @var \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Request */
    private $request;
    /** @var \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Session\Session */
    private $session;
    /** @var \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Translation\TranslatorInterface */
    private $translator;
    /** @var LoginFormCreatedListener */
    private $SUT;

    ### TESTS ###

    public function testDoesNothingOnDifferentForm()
    {
        $this->formIsNotLogin();

        $this->formAddErrorIsNotCalled();
        $this->SUT->onFormCreated($this->event);
    }

    public function testDoesNothingWhenNoError()
    {
        $this->formIsLogin();

        $this->requestHasNoError();
        $this->sessionHasNoError();

        $this->formAddErrorIsNotCalled();
        $this->SUT->onFormCreated($this->event);
    }

    public function testSetsRequestError()
    {
        $exception = new \Exception('foo');

        $this->formIsLogin();

        $this->requestErrorIs($exception);

        $this->formAddErrorIsCalledWith('foo');
        $this->SUT->onFormCreated($this->event);
    }

    public function testSetsSessionError()
    {
        $exception = new \Exception('bar');

        $this->formIsLogin();

        $this->requestHasNoError();
        $this->sessionErrorIs($exception);

        $this->formAddErrorIsCalledWith('bar');
        $this->SUT->onFormCreated($this->event);
    }

    public function testTranslatesBadCredentialsError()
    {
        $exception = new BadCredentialsException('foo');

        $this->formIsLogin();

        $this->requestErrorIs($exception);
        $this->exceptionMessageIsTranslated('bar');

        $this->formAddErrorIsCalledWith('bar');
        $this->SUT->onFormCreated($this->event);
    }

    ### SETUP ###

    public function setUp()
    {
        $this->session    = $this->getMock('\Symfony\Component\HttpFoundation\Session\Session');
        $this->translator = $this->getMock('\Symfony\Component\Translation\TranslatorInterface');

        $this->SUT = new LoginFormCreatedListener($this->session, $this->translator);

        // Mock the event with request and form
        $this->request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->enableArgumentCloning()
            ->getMock();

        $this->form = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();

        $this->event = $this->getMockBuilder('Brammm\CommonBundle\Event\FormCreatedEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $this->event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($this->request));
        $this->event->expects($this->once())
            ->method('getForm')
            ->will($this->returnValue($this->form));
    }

    ### HELPERS ###

    private function exceptionMessageIsTranslated($translation)
    {
        $this->translator->expects($this->once())
            ->method('trans')
            ->with($this->equalTo('bad_credentials'))
            ->will($this->returnValue($translation));
    }

    private function requestHasNoError()
    {
        $this->requestErrorIs();
    }

    private function requestErrorIs(\Exception $exception = null)
    {
        $hasError = null === $exception ? false : true;

        $paramBag = $this->getMock('Symfony\Component\HttpFoundation\ParameterBag');
        $paramBag->expects($this->once())
            ->method('has')
            ->will($this->returnValue($hasError));

        if ($hasError) {
            $paramBag->expects($this->once())
                ->method('get')
                ->will($this->returnValue($exception));
        } else {
            $paramBag->expects($this->never())
                ->method('get');
        }
        $this->request->attributes = $paramBag;
    }

    private function sessionHasNoError()
    {
        $this->sessionErrorIs();
    }

    private function sessionErrorIs(\Exception $exception = null)
    {
        $this->session
            ->expects($this->once())
            ->method('remove')
            ->with($this->equalTo(SecurityContext::AUTHENTICATION_ERROR))
            ->will($this->returnValue($exception));
    }

    private function formIsNotLogin()
    {
        $this->form
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('foo'));
    }

    private function formIsLogin()
    {
        $this->form
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('login'));
    }

    private function formAddErrorIsNotCalled()
    {
        $this->form->expects($this->never())
            ->method('addError');
    }

    private function formAddErrorIsCalledWith($message)
    {
        $error = new FormError($message);

        $this->form->expects($this->once())
            ->method('addError')
            ->with($this->equalTo($error));
    }
}