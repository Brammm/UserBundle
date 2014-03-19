<?php

namespace Brammm\UserBundle\Services;

use Brammm\UserBundle\Entity\Repository\UserRepository;
use Brammm\UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var UserRepository */
    protected $repository;
    /** @var Canonicalizer */
    protected $canonicalizer;

    public function __construct(EntityManagerInterface $em, UserRepository $repository, Canonicalizer $canonicalizer)
    {
        $this->em            = $em;
        $this->repository    = $repository;
        $this->canonicalizer = $canonicalizer;
    }

    /**
     * @param $email
     *
     * @return null|User
     */
    public function findUser($email)
    {
        return $this->repository->findOneBy(['emailCanonical' => $this->canonicalizer->canonicalize($email)]);
    }

    /**
     * @param array $by
     *
     * @return null|User
     */
    public function findUserBy(array $by)
    {
        return $this->repository->findOneBy($by);
    }

    public function register(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

} 