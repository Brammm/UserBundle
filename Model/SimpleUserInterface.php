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
     * Set a user's hashed password
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password);

    /**
     * Retreives a user's hashed password
     *
     * @return string
     */
    public function getPassword();

    /**
     * Gets a user's plain password (non-hashed)
     *
     * @return string
     */
    public function getPlainPassword();
} 