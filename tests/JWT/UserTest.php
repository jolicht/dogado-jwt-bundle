<?php

namespace JWT;

use Jolicht\DogadoJwtBundle\JWT\Client;
use Jolicht\DogadoJwtBundle\JWT\Tenant;
use Jolicht\DogadoJwtBundle\JWT\User;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\DogadoJwtBundle\JWT\User
 */
class UserTest extends TestCase
{
    private Tenant $tenant;
    private Client $client;
    private User $user;

    protected function setUp(): void
    {
        $this->tenant = new Tenant(
            '741bd893-01ab-4e16-b879-cb56388aa96e',
            'testTenantCode',
            'testTenantName'
        );
        $this->client = new Client(
            'ae29436e-5ec2-4b53-9537-6699b0fbaeb2',
            'testClientCode',
            'testClientName',
            $this->tenant
        );
        $this->user = new User(
            '1c963442-ae30-4fca-85c2-c4a4287e040e',
            'testUsername',
            ['TEST_ROLE'],
            $this->client
        );
    }

    public function testGetId(): void
    {
        $this->assertSame('1c963442-ae30-4fca-85c2-c4a4287e040e', $this->user->getId());
    }

    public function testGetName(): void
    {
        $this->assertSame('testUsername', $this->user->getName());
    }

    public function testGetRoles(): void
    {
        $this->assertSame(['TEST_ROLE'], $this->user->getRoles());
    }

    public function testGetClient(): void
    {
        $this->assertSame($this->client, $this->user->getClient());
    }

    public function testFromArray(): void
    {
        $data = [
            'id' => '1c963442-ae30-4fca-85c2-c4a4287e040e',
            'name' => 'testUsername',
            'roles' => ['TEST_ROLE'],
            'client' => [
                'id' => 'ae29436e-5ec2-4b53-9537-6699b0fbaeb2',
                'code' => 'testClientCode',
                'name' => 'testClientName',
                'tenant' => [
                    'id' => '741bd893-01ab-4e16-b879-cb56388aa96e',
                    'code' => 'testTenantCode',
                    'name' => 'testTenantName',
                ],
            ],
        ];

        $this->assertEquals($this->user, User::fromArray($data));
    }
}
