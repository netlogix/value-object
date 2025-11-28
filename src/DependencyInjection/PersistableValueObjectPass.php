<?php

declare(strict_types=1);

namespace Netlogix\ValueObject\DependencyInjection;

use Doctrine\ORM\Mapping\ChainTypedFieldMapper;
use Netlogix\ValueObject\Doctrine\PersistableValueObjectTypes;
use Netlogix\ValueObject\Doctrine\TypedFieldMapper\PersistableValueObjectTypedFieldMapper;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class PersistableValueObjectPass implements CompilerPassInterface
{
    public const string VALUE_OBJECT_TAG = 'value_object.persistable_value_object';

    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        $types = [];

        foreach ($container->getDefinitions() as $definition) {
            if (
                !$definition->hasTag(PersistableValueObjectPass::VALUE_OBJECT_TAG) ||
                $definition->isAbstract() ||
                ($class = $definition->getClass()) === null
            ) {
                continue;
            }

            $types[$class] = $class;
        }

        $container->getDefinition(PersistableValueObjectTypes::class)->setArgument('$types', $types);

        $chainTypedFieldMapper = new Definition(ChainTypedFieldMapper::class);
        $chainTypedFieldMapper->setArgument('$typedFieldMappers', [
            new Reference(PersistableValueObjectTypedFieldMapper::class),
            new Reference('doctrine.orm.typed_field_mapper.default'),
        ]);

        $container->setDefinition('doctrine.orm.typed_field_mapper.chain', $chainTypedFieldMapper);
    }
}
