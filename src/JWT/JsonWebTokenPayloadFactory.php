<?php

declare(strict_types=1);

namespace Jolicht\DogadoJwtBundle\JWT;

use Symfony\Component\HttpFoundation\RequestStack;
use Webmozart\Assert\Assert;

final class JsonWebTokenPayloadFactory implements PayloadFactory
{
    public function __construct(
        private readonly RequestStack $requestStack
    ) {
    }

    public function __invoke(): ?JsonWebTokenPayload
    {
        $request = $this->requestStack->getCurrentRequest();
        Assert::notNull($request);
        $requestHeaders = $request->headers;

        if (false === $requestHeaders->has('authorization')) {
            return null;
        }

        $authorizationHeader = $request->headers->get('authorization');
        Assert::notNull($authorizationHeader);

        Assert::startsWith($authorizationHeader, 'Bearer ', 'Authorization header must start with \'Bearer \'');

        $token = \substr($authorizationHeader, 7);

        $parts = \explode('.', $token);
        Assert::count($parts, 3, 'Token must have 3 segments.');

        $base64DecodedPayload = \base64_decode($parts[1]);

        $payloadContent = \json_decode($base64DecodedPayload, true);
        Assert::isArray($payloadContent, 'Cannot json decode payload.');
        Assert::isArray($payloadContent['user']);

        return new JsonWebTokenPayload(
            subject: (string) $payloadContent['sub'],
            user: User::fromArray($payloadContent['user'])
        );
    }
}
