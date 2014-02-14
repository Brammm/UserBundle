<?php

namespace Brammm\UserBundle\EventListener;

use Brammm\CommonBundle\Event\FormCreatedEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Translation\TranslatorInterface;

class LoginFormCreatedListener
{
    /** @var SessionInterface */
    protected $session;
    /** @var TranslatorInterface */
    protected $translator;

    public function __construct(SessionInterface $session, TranslatorInterface $translator)
    {
        $this->session    = $session;
        $this->translator = $translator;
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
            ? $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR)
            : $this->session->remove(SecurityContext::AUTHENTICATION_ERROR);

        if (null !== $exception) {
            switch (true) {
                case $exception instanceof BadCredentialsException:
                    $message = $this->translator->trans('bad_credentials');
                break;
                default:
                    $message = $exception->getMessage();
            }

            $form->addError(new FormError($message));
        }

    }
} 