<?php

namespace Jolicht\DogadoJwtBundle\JWT;

interface PayloadFactory
{
    public function __invoke(): ?JsonWebTokenPayload;
}
