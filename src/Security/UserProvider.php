<?php

namespace Jolicht\DogadoJwtBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return \Jolicht\DogadoJwtBundle\JWT\User::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        throw new \Exception('not implemented');
    }
}
