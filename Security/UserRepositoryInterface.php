<?php

namespace Brammm\UserBundle\Security;

interface UserRepositoryInterface
{
    /**
     * Looks for a SimpleUserInterface user
     * Must return one or null
     *
     * @param string $username
     *
     * @return \Brammm\UserBundle\Model\SimpleUserInterface|null
     */
    public function findOneByUsername($username);
} 