<?php

namespace EventListener;

use Jolicht\DogadoJwtBundle\EventListener\SetUserKernelRequestEventListener;
use Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayload;
use Jolicht\DogadoJwtBundle\JWT\PayloadFactory;
use Jolicht\DogadoUser\Client;
use Jolicht\DogadoUser\ClientId;
use Jolicht\DogadoUser\Tenant;
use Jolicht\DogadoUser\TenantId;
use Jolicht\DogadoUser\User;
use Jolicht\DogadoUser\UserId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @covers \Jolicht\DogadoJwtBundle\EventListener\SetUserKernelRequestEventListener
 */
class SetUserKernelRequestEventListenerTest extends TestCase
{
    private TokenStorageInterface|MockObject $tokenStorage;
    private PayloadFactory $payloadFactory;
    private SetUserKernelRequestEventListener $listener;

    protected function setUp(): void
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->payloadFactory = $this->createStub(PayloadFactory::class);
        $this->listener = new SetUserKernelRequestEventListener($this->payloadFactory, $this->tokenStorage);
    }

    public function testOnKernelRequestNoTokenGivenDoesNotSetAuthenticatedUser(): void
    {
        $this->payloadFactory
            ->method('__invoke')
            ->willReturn(null);

        $this->tokenStorage
            ->expects($this->never())
            ->method('setToken');

        $event = $this->createMock(RequestEvent::class);
        $this->listener->onKernelRequest($event);
    }

    public function testOnKernelRequestTokenGivenSetsAuthenticatedUser(): void
    {
        $user = new User(
            UserId::fromString('1c963442-ae30-4fca-85c2-c4a4287e040e'),
            'testUsername',
            ['TEST_ROLE'],
            new Client(
                ClientId::fromString('ae29436e-5ec2-4b53-9537-6699b0fbaeb2'),
                'testClientCode',
                'testClientName',
                new Tenant(
                    TenantId::fromString('741bd893-01ab-4e16-b879-cb56388aa96e'),
                    'testTenantCode',
                    'testTenantName'
                )
            )
        );
        $token = new JsonWebTokenPayload('testUsername', $user);

        $this->payloadFactory
            ->method('__invoke')
            ->willReturn($token);

        $this->tokenStorage
            ->expects($this->once())
            ->method('setToken')
            ->with($this->equalTo(new UsernamePasswordToken($user, 'main', $user->getRoles())));

        $event = $this->createMock(RequestEvent::class);
        $this->listener->onKernelRequest($event);
    }
}
