<?php

namespace JWT;

use Jolicht\DogadoJwtBundle\JWT\Tenant;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\DogadoJwtBundle\JWT\Tenant
 */
class TenantTest extends TestCase
{
    private Tenant $tenant;

    protected function setUp(): void
    {
        $this->tenant = new Tenant('79f8eb50-3e81-428f-b123-0308adfc6431', 'testCode', 'testName');
    }

    public function testGetId(): void
    {
        $this->assertSame('79f8eb50-3e81-428f-b123-0308adfc6431', $this->tenant->getId());
    }

    public function testGetCode(): void
    {
        $this->assertSame('testCode', $this->tenant->getCode());
    }

    public function testGetName(): void
    {
        $this->assertSame('testName', $this->tenant->getName());
    }

    public function testFromArray(): void
    {
        $data = [
            'id' => '79f8eb50-3e81-428f-b123-0308adfc6431',
            'code' => 'testCode',
            'name' => 'testName',
        ];

        $this->assertEquals($this->tenant, Tenant::fromArray($data));
    }
}
