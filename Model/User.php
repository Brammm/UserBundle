<?php

namespace Brammm\UserBundle\Model;

use Brammm\UserBundle\Model\SimpleUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, \Serializable
{
    const DEFAULT_ROLE = 'ROLE_USER';

    protected $username;
    protected $password;


    public function __construct(SimpleUserInterface $user)
    {
        $this->username = $user->getUsername();
        $this->password = $user->getPassword();
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize([
            $this->username,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        list($this->username) = unserialize($serialized);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        return [self::DEFAULT_ROLE];
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritDoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials()
    {
        // DOES NOTHING
    }
}