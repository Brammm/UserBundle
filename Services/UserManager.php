<?php

namespace Brammm\UserBundle\Services;

use Brammm\UserBundle\Entity\Repository\UserRepository;

class UserManager
{
    /** @var UserRepository */
    protected $repository;
    /** @var Canonicalizer */
    protected $canonicalizer;

    public function __construct(UserRepository $repository, Canonicalizer $canonicalizer)
    {
        $this->repository    = $repository;
        $this->canonicalizer = $canonicalizer;
    }

    public function findUser($email)
    {
        return $this->repository->findOneBy(['emailCanonical' => $this->canonicalizer->canonicalize($email)]);
    }

    public function findUserBy(array $by)
    {
        return $this->repository->findOneBy($by);
    }

} 