<?php

declare(strict_types=1);

namespace JWT;

use Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayload;
use Jolicht\DogadoUser\Client;
use Jolicht\DogadoUser\ClientId;
use Jolicht\DogadoUser\Tenant;
use Jolicht\DogadoUser\TenantId;
use Jolicht\DogadoUser\User;
use Jolicht\DogadoUser\UserId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayload
 */
class JsonWebTokenPayloadTest extends TestCase
{
    private Tenant $tenant;
    private Client $client;
    private User $user;
    private JsonWebTokenPayload $payload;

    protected function setUp(): void
    {
        $this->tenant = new Tenant(
            id: TenantId::create(),
            code: 'testCode',
            name: 'testName'
        );

        $this->client = new Client(
            id: ClientId::create(),
            code: 'testCode',
            name: 'testName',
            tenant: $this->tenant
        );

        $this->user = new User(
            id: UserId::create(),
            name: 'testName',
            roles: ['ROLE_USER'],
            client: $this->client
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
