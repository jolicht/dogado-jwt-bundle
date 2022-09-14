<?php

declare(strict_types=1);

namespace JWT;

use Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayload;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayload
 */
class JsonWebTokenPayloadTest extends TestCase
{
    private JsonWebTokenPayload $payload;

    protected function setUp(): void
    {
        $this->payload = new JsonWebTokenPayload('testSubject');
    }

    public function testGetSubjectHasSubjectReturnsSubject(): void
    {
        $this->assertSame('testSubject', $this->payload->getSubject());
    }
}
