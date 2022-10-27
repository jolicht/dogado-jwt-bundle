<?php

namespace Jolicht\DogadoJwtBundle\JWT;

use Webmozart\Assert\Assert;

/**
 * @psalm-immutable
 */
final class User
{
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

        return new self(
            id: (string) $data['id'],
            name: (string) $data['name'],
            roles: $data['roles'],
            client: Client::fromArray($data['client'])
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
