<?php

declare(strict_types=1);

namespace Jolicht\DogadoJwtBundle\JWT;

final class JsonWebTokenPayload
{
    public function __construct(
        private readonly string $subject,
        private readonly User $user
    ) {
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getClient(): Client
    {
        return $this->user->getClient();
    }

    public function getTenant(): Tenant
    {
        return $this->getClient()->getTenant();
    }
}
