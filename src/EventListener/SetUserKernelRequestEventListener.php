<?php

namespace Jolicht\DogadoJwtBundle\EventListener;

use Jolicht\DogadoJwtBundle\JWT\PayloadFactory;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

final class SetUserKernelRequestEventListener
{
    public function __construct(
        private readonly PayloadFactory $payloadFactory,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $jsonWebToken = $this->payloadFactory->__invoke();
        if (null === $jsonWebToken) {
            return;
        }

        $user = $jsonWebToken->getUser();

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
    }
}
