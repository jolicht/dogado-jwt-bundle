<?php

namespace Jolicht\DogadoJwtBundle\JWT;

use Webmozart\Assert\Assert;

/**
 * @psalm-immutable
 */
final class Client
{
    public function __construct(
        private readonly string $id,
        private readonly string $code,
        private readonly string $name,
        private readonly Tenant $tenant
    ) {
        Assert::uuid($this->id);
    }

    public static function fromArray(array $data): self
    {
        Assert::isArray($data['tenant']);

        return new self(
            id: (string) $data['id'],
            code: (string) $data['code'],
            name: (string) $data['name'],
            tenant: Tenant::fromArray($data['tenant'])
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTenant(): Tenant
    {
        return $this->tenant;
    }
}
