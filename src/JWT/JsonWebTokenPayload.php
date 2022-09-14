<?php

declare(strict_types=1);

namespace Jolicht\DogadoJwtBundle\JWT;

final class JsonWebTokenPayload
{
    public function __construct(
        private readonly string $subject
    ) {
    }

    public function getSubject(): string
    {
        return $this->subject;
    }
}
