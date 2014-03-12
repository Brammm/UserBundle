<?php

namespace Brammm\UserBundle\Form\Model;

class Login
{
    /** @var string */
    protected $username;
    /** @var string */
    protected $password;
    /** @var boolean */
    protected $rememberMe;

    public function __construct($username = null)
    {
        if (null !== $username) {
            $this->setUsername($username);
        }

        $this->setRememberMe(true);
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param boolean $rememberMe
     *
     * @return $this
     */
    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getRememberMe()
    {
        return $this->rememberMe;
    }

}