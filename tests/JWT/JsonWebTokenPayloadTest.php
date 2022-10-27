<?php

declare(strict_types=1);

namespace JWT;

use Jolicht\DogadoJwtBundle\JWT\Client;
use Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayload;
use Jolicht\DogadoJwtBundle\JWT\Tenant;
use Jolicht\DogadoJwtBundle\JWT\User;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayload
 */
class JsonWebTokenPayloadTest extends TestCase
{
    private User $user;
    private Client $client;
    private Tenant $tenant;
    private JsonWebTokenPayload $payload;

    protected function setUp(): void
    {
        $this->tenant = new Tenant(
            'ae09033e-0e3f-46d5-a425-301cea666669',
            'testCode',
            'testName',
        );

        $this->client = new Client(
            'bd7ffd18-ed65-4dcc-b931-26bacde5e280',
            'testCode',
            'testName',
            $this->tenant
        );
        $this->user = new User(
            '316bafbb-f2fc-4190-a439-2c366ed0c470',
            'testName',
            [],
            $this->client
        );
        $this->payload = new JsonWebTokenPayload('testSubject', $this->user);
    }

    public function testGetSubjectHasSubjectReturnsSubject(): void
    {
        $this->assertSame('testSubject', $this->payload->getSubject());
    }

    public function testGetUserHasUserReturnsUser(): void
    {
        $this->assertSame($this->user, $this->payload->getUser());
    }

    public function testGetClient(): void
    {
        $this->assertSame($this->client, $this->payload->getClient());
    }

    public function testGetTenant(): void
    {
        $this->assertSame($this->tenant, $this->payload->getTenant());
    }
}
