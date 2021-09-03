<?php

declare(strict_types=1);

namespace Crell\SerializerTest;

use Symfony\Component\Serializer\Mapping\ClassDiscriminatorMapping;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;

class ManualDiscriminator implements ClassDiscriminatorResolverInterface
{
    /** @var ClassDiscriminatorMapping[]  */
    protected array $mapping = [];

    public function addMap(string $baseClass, string $type, array $map): static
    {
        $this->mapping[$baseClass] = new ClassDiscriminatorMapping($type, $map);
        return $this;
    }

    public function getMappingForClass(string $class): ?ClassDiscriminatorMapping
    {
        if (!array_key_exists($class, $this->mapping)) {
            $this->deriveMappingFor($class);
        }

        return $this->mapping[$class];
    }

    /**
     * @inheritDoc
     */
    public function getMappingForMappedObject($object): ?ClassDiscriminatorMapping
    {
        // I don't really understand why this method is separate from the class one.
        // The lack of any documentation whatsoever doesn't help.
        return $this->getMappingForClass(get_class($object));
    }

    /**
     * @inheritDoc
     */
    public function getTypeForMappedObject($object): ?string
    {
        return $this->getMappingForMappedObject($object)?->getMappedObjectType($object) ?? null;
    }

    private function deriveMappingFor(string $class): void
    {
        // @todo Improve this to also scan for interfaces.
        $parent = $this->getParent($class);
        $this->mapping[$class] = $parent ? $this->getMappingForClass($parent) : null;
    }

    private function getParent(string $class): ?string
    {
        $rClass = new \ReflectionClass($class);
        $rParent = $rClass->getParentClass();
        return $rParent ? $rParent->name : null;
    }

    private function resolveMappingForMappedObject($object)
    {
        print __FUNCTION__ . PHP_EOL;
        $reflectionClass = new \ReflectionClass($object);
        if ($parentClass = $reflectionClass->getParentClass()) {
            return $this->getMappingForMappedObject($parentClass->getName());
        }

        foreach ($reflectionClass->getInterfaceNames() as $interfaceName) {
            if (null !== ($interfaceMapping = $this->getMappingForMappedObject($interfaceName))) {
                return $interfaceMapping;
            }
        }

        return null;
    }
}
