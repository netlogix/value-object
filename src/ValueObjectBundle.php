<?php

declare(strict_types=1);

namespace Netlogix\ValueObject;

use Doctrine\DBAL\Types\Type;
use Netlogix\ValueObject\DependencyInjection\PersistableValueObjectPass;
use Netlogix\ValueObject\Doctrine\PersistableValueObjectTypes;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class ValueObjectBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $services = $container->services();
        $services->defaults()
            ->autoconfigure()
            ->autowire();

        $services->load('Netlogix\\ValueObject\\', '../src');
    }

    public function prependExtension(
        ContainerConfigurator $container,
        ContainerBuilder $builder
    ): void {
        $builder->prependExtensionConfig('doctrine', [
            'orm' => [
                'typed_field_mapper' => 'doctrine.orm.typed_field_mapper.chain',
            ],
        ]);
    }

    public function build(ContainerBuilder $container): void
    {
        // Register compiler pass to collect value object types
        $container->addCompilerPass(new PersistableValueObjectPass());
    }

    public function boot(): void
    {
        // Register custom Doctrine types for value objects
        $types = $this->container->get(PersistableValueObjectTypes::class);
        $types->register(Type::getTypeRegistry());
    }
}
