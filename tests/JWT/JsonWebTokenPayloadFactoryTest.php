<?php

declare(strict_types=1);

namespace JWT;

use Jolicht\DogadoJwtBundle\JWT\Client;
use Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayload;
use Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayloadFactory;
use Jolicht\DogadoJwtBundle\JWT\User;
use PHPUnit\Framework\TestCase;
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
        $this->request->headers->set('authorization', 'Bearer '.$this->getEncodedToken());

        $payload = $this->factory->__invoke();
        $this->assertInstanceOf(JsonWebTokenPayload::class, $payload);
        $this->assertSame('test', $payload->getSubject());

        $user = $payload->getUser();
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('da0a0dfa-919b-42f1-935e-1454b44b2a1a', $user->getId());
        $this->assertSame('test', $user->getName());
        $this->assertSame(['ROLE_USER'], $user->getRoles());

        $client = $user->getClient();
        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame('ea686b7f-4789-4ff4-b589-116a92e827fc', $client->getId());
        $this->assertSame('cloudpit', $client->getCode());
        $this->assertSame('CloudPit', $client->getName());

        $tenant = $client->getTenant();
        $this->assertSame('e07240fd-8984-4491-a7a2-61a881ddb991', $tenant->getId());
        $this->assertSame('dogado', $tenant->getCode());
        $this->assertSame('dogado GmbH', $tenant->getName());
    }

    public function testInvokeRequestHeaderHasNoAuthorizationKeyReturnsNull(): void
    {
        $this->assertNull($this->factory->__invoke());
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
        $encodedToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.'.
            \base64_encode('"sub":"1234567890","name":"John Doe","iat":1516239022}').'.'.
            'SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
        $this->request->headers->set('authorization', 'Bearer '.$encodedToken);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot json decode payload.');

        $this->factory->__invoke();
    }

    private function getEncodedToken(): string
    {
        return 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NjY4NDc5NjgsImV4cCI6MTY2Njg1MTU2OCwic3ViIjoidGVzdCIsInJvbGVzIjpbIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6InRlc3QiLCJ1c2VyIjp7ImlkIjoiZGEwYTBkZmEtOTE5Yi00MmYxLTkzNWUtMTQ1NGI0NGIyYTFhIiwibmFtZSI6InRlc3QiLCJyb2xlcyI6WyJST0xFX1VTRVIiXSwiY2xpZW50Ijp7ImlkIjoiZWE2ODZiN2YtNDc4OS00ZmY0LWI1ODktMTE2YTkyZTgyN2ZjIiwiY29kZSI6ImNsb3VkcGl0IiwibmFtZSI6IkNsb3VkUGl0IiwidGVuYW50Ijp7ImlkIjoiZTA3MjQwZmQtODk4NC00NDkxLWE3YTItNjFhODgxZGRiOTkxIiwiY29kZSI6ImRvZ2FkbyIsIm5hbWUiOiJkb2dhZG8gR21iSCJ9fX19.JyetWWwnAL6k5sKpQyNtNjpiRCf_qcMB4JGu246VpIo8EuUvKnwczJtZzfOWtjqm-7i4-K15PWpiQy1zU7FpLWBtl1HIHO7pYYhHxSxjpdIzTIP39OdRSyUhNhPhpABSOof_2QA80WYh0guZ_TCSITEvS0bav95Kq5DezF3wP0Kui45Ogqv6Fk3egUbnlWMrKX_osMUqLiqTYASKOgQ0aOKfLJg1qBRYS4_8QbuoTRFnBaoLohpmqAZ9k8B_biVDCm-ua_40HgODr6gvDHvG48ipcY7YqHUMSXrBju9m9HL8uk60E6H3Ri9hdvJ06P5q84a_R-GMFTRhBzB0RxujTg';
    }
}
