<?php

declare(strict_types=1);

namespace JWT;

use function base64_encode;

use Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayload;
use Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayloadFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @covers \Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayloadFactory
 */
class JsonWebTokenPayloadFactoryTest extends TestCase
{
    private RequestStack $requestStack;
    private Request $request;
    private HeaderBag $headerBag;
    private JsonWebTokenPayloadFactory $factory;

    protected function setUp(): void
    {
        $this->requestStack = new RequestStack();
        $this->request = new Request();
        $this->requestStack->push($this->request);
        $this->factory = new JsonWebTokenPayloadFactory($this->requestStack);
    }

    public function testInvokeHasValidPayloadReturnsPayload(): void
    {
        $encodedToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
        $this->request->headers->set('authorization', 'Bearer '.$encodedToken);

        $payload = $this->factory->__invoke();
        $this->assertInstanceOf(JsonWebTokenPayload::class, $payload);
        $this->assertSame('1234567890', $payload->getSubject());
    }

    public function testInvokeRequestHeaderHasNoAuthorizationKeyThrowsInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Authorization header not found.');

        $this->factory->__invoke();
    }

    public function testInvokeTokenHasNot3PartsThrowsInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Token must have 3 segments.');

        $encodedToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ';
        $this->request->headers->set('authorization', 'Bearer '.$encodedToken);

        $this->factory->__invoke();
    }

    public function testInvokeAuthorizationHeaderDoesNotStartWithBearerThrowsInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Authorization header must start with \'Bearer \'');

        $this->request->headers->set('authorization', 'invalid');

        $this->factory->__invoke();
    }

    public function testInvokeCannotDecodePayloadThrowsInvalidArgumentException(): void
    {
        $t = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $encodedToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.'.
            base64_encode('"sub":"1234567890","name":"John Doe","iat":1516239022}').'.'.
            'SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
        $this->request->headers->set('authorization', 'Bearer '.$encodedToken);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot json decode payload.');

        $this->factory->__invoke();
    }
}
