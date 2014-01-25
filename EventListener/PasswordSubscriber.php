<?php

namespace Brammm\UserBundle\EventListener;

use Brammm\UserBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class PasswordSubscriber implements EventSubscriber
{
    /** @var EncoderFactoryInterface */
    protected $encoderFactory;

    function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }


    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate'
        ];
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $this->encodePassword($event);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->encodePassword($event);
    }

    /**
     * If set, encodes a plain password using the configured encoder
     *
     * @param LifecycleEventArgs $event
     */
    private function encodePassword(LifecycleEventArgs $event)
    {
        $user = $event->getObject();

        if (!$user instanceof User) {
            return;
        }

        if (null === $user->getPlainPassword()) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        $user->setPassword(
            $encoder->encodePassword($user->getPlainPassword(), $user->getSalt())
        );
    }
}