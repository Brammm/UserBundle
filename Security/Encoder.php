<?php

namespace Brammm\UserBundle\Security;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class Encoder implements PasswordEncoderInterface
{
    /**
     * @var PasswordEncoderInterface
     */
    protected $encoder;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoder = $encoderFactory->getEncoder('Brammm\UserBundle\Model\User');
    }

    /**
     * {@inheritDoc}
     */
    public function encodePassword($raw, $salt)
    {
        return $this->encoder->encodePassword($raw, $salt);
    }

    /**
     * {@inheritDoc}
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $this->encoder->isPasswordValid($encoded, $raw, $salt);
    }
}