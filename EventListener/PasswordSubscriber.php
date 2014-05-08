<?php

namespace Brammm\UserBundle\EventListener;

use Brammm\UserBundle\Model\SimpleUserInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class PasswordSubscriber implements EventSubscriber
{
    /**
     * @var PasswordEncoderInterface
     */
    protected $encoder;

    function __construct(PasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
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

        if (!$user instanceof SimpleUserInterface) {
            return;
        }

        if (null === $user->getPlainPassword()) {
            return;
        }

        $user->setPassword(
            $this->encoder->encodePassword($user->getPlainPassword(), null)
        );
    }
}