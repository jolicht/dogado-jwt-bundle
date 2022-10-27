<?php

declare(strict_types=1);

namespace DependencyInjection;

use Jolicht\DogadoJwtBundle\DependencyInjection\DogadoJwtExtension;
use Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayload;
use Jolicht\DogadoJwtBundle\JWT\JsonWebTokenPayloadFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Jolicht\DogadoJwtBundle\DependencyInjection\DogadoJwtExtension
 */
class DogadoJwtExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $extension = new DogadoJwtExtension();
        $containerBuilder = new ContainerBuilder();
        $config = [];
        $extension->load($config, $containerBuilder);

        $this->assertTrue($containerBuilder->has(JsonWebTokenPayloadFactory::class));
        $this->assertTrue($containerBuilder->has(JsonWebTokenPayload::class));
    }
}
