<?php

namespace JWT;

use Jolicht\DogadoJwtBundle\JWT\Client;
use Jolicht\DogadoJwtBundle\JWT\Tenant;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\DogadoJwtBundle\JWT\Client
 */
class ClientTest extends TestCase
{
    private Tenant $tenant;
    private Client $client;

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
    }

    public function testGetId(): void
    {
        $this->assertSame('ae29436e-5ec2-4b53-9537-6699b0fbaeb2', $this->client->getId());
    }

    public function testGetCode(): void
    {
        $this->assertSame('testClientCode', $this->client->getCode());
    }

    public function testGetName(): void
    {
        $this->assertSame('testClientName', $this->client->getName());
    }

    public function testGetTenant(): void
    {
        $this->assertSame($this->tenant, $this->client->getTenant());
    }

    public function testFromArray(): void
    {
        $data = [
            'id' => 'ae29436e-5ec2-4b53-9537-6699b0fbaeb2',
            'code' => 'testClientCode',
            'name' => 'testClientName',
            'tenant' => [
                'id' => '741bd893-01ab-4e16-b879-cb56388aa96e',
                'code' => 'testTenantCode',
                'name' => 'testTenantName',
            ],
        ];
        $this->assertEquals($this->client, Client::fromArray($data));
    }
}
