<?php

namespace Brammm\UserBundle\EventListener;

use Brammm\UserBundle\Entity\User;
use Brammm\UserBundle\Services\CanonicalizerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class CanonicalizerSubscriber implements EventSubscriber
{
    /** @var CanonicalizerInterface */
    private $canonicalizer;

    public function __construct(CanonicalizerInterface $canonicalizer)
    {
        $this->canonicalizer = $canonicalizer;
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
        $this->canonicalize($event);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->canonicalize($event);
    }

    /**
     * Sets a canonical email
     *
     * @param LifecycleEventArgs $event
     */
    private function canonicalize(LifecycleEventArgs $event)
    {
        $user = $event->getObject();
        if ($user instanceof User) {
            $user->setEmailCanonical(
                $this->canonicalizer->canonicalize($user->getEmail())
            );
        }
    }
}