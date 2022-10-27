<?php

namespace Jolicht\DogadoJwtBundle\JWT;

use Webmozart\Assert\Assert;

/**
 * @psalm-immutable
 */
final class Tenant
{
    public function __construct(
        private readonly string $id,
        private readonly string $code,
        private readonly string $name
    ) {
        Assert::uuid($this->id);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) $data['id'],
            code: (string) $data['code'],
            name: (string) $data['name']
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
}
