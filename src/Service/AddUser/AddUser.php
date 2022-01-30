<?php

namespace App\Service\AddUser;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AddUser
{
    private $parameterBag;
    private $passwordHasher;
    private $doctrine;

    public function __construct(
        ParameterBagInterface $parameterBag,
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine
    ) {
        $this->parameterBag = $parameterBag;
        $this->passwordHasher = $passwordHasher;
        $this->doctrine = $doctrine;
    }

    public function addAnonymousUser(): User
    {
        $anonymousUserEmail = $this->parameterBag->get('app.anonymous_user_email');

        $anonymousUser = new User();
        $anonymousUser->setFullName('Anonymous User');
        $anonymousUser->setUsername('anonymous_user');
        $anonymousUser->setPassword($this->passwordHasher->hashPassword($anonymousUser, 'CvaC6n57E5'));
        $anonymousUser->setEmail($anonymousUserEmail);
        $anonymousUser->setRoles(['PUBLIC_ACCESS']);

        $em = $this->doctrine->getManager();
        $em->persist($anonymousUser);
        $em->flush();

        return $anonymousUser;
    }
}
