<?php

namespace Brammm\UserBundle\Model;

interface SimpleUserInterface
{
    /**
     * Retreives a username for the user
     *
     * @return string
     */
    public function getUsername();

    /**
     * Retreives a user's password
     *
     * @return string
     */
    public function getPassword();
} 