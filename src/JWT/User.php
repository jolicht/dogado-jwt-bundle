<?php

namespace Jolicht\DogadoJwtBundle\JWT;

use Symfony\Component\Security\Core\User\UserInterface;
use Webmozart\Assert\Assert;

/**
 * @psalm-immutable
 */
final class User implements UserInterface
{
    /**
     * @param string[] $roles
     */
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly array $roles,
        private readonly Client $client
    ) {
        Assert::uuid($this->id);
    }

    public static function fromArray(array $data): self
    {
        Assert::isArray($data['roles']);
        Assert::isArray($data['client']);
        Assert::allString($data['roles']);

        return new self(
            id: (string) $data['id'],
            name: (string) $data['name'],
            roles: $data['roles'],
            client: Client::fromArray($data['client'])
        );
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getName();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getTenant(): Tenant
    {
        return $this->getClient()->getTenant();
    }
}
