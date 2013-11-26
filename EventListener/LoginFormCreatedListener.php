<?php

namespace Brammm\UserBundle\EventListener;

use Brammm\CommonBundle\Event\FormCreatedEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\SecurityContext;

class LoginFormCreatedListener
{
    /** @var SessionInterface */
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function onFormCreated(FormCreatedEvent $event)
    {
        $request = $event->getRequest();
        $form    = $event->getForm();

        if ('login' !== $form->getName()) {
            return;
        }

        // get the login error if there is one
        $exception = $request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)
            ? $exception = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR)
            : $this->session->remove(SecurityContext::AUTHENTICATION_ERROR);

        if (null !== $exception) {
            $form->addError(new FormError($exception->getMessage()));
        }

    }
} 