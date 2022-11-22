<?php

namespace Security;

use Jolicht\DogadoJwtBundle\Security\UserProvider;
use Jolicht\DogadoUser\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \Jolicht\DogadoJwtBundle\Security\UserProvider
 */
class UserProviderTest extends TestCase
{
    private UserProvider $userProvider;

    protected function setUp(): void
    {
        $this->userProvider = new UserProvider();
    }

    public function testRefreshUserReturnsGivenUser(): void
    {
        $user = $this->createMock(UserInterface::class);

        $this->assertSame($user, $this->userProvider->refreshUser($user));
    }

    public function testSupportsClassIsDogadoJWTUserReturnsTrue(): void
    {
        $this->assertTrue($this->userProvider->supportsClass(User::class));
    }

    public function testSupportsClassIsNotDogadoJWTUserReturnsFalse(): void
    {
        $this->assertFalse($this->userProvider->supportsClass(\stdClass::class));
    }

    public function testLoadUserByIdentifierThrowsNotImplementedExeption(): void
    {
        $this->expectException(\Exception::class);
        $this->userProvider->loadUserByIdentifier('testIdentifier');
    }
}
