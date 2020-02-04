<?php

namespace Application\Infrastructure\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Laminas\Hydrator\DoctrineObject;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DoctrineObjectHydratorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DoctrineObject($container->get(ObjectManager::class), false);
    }
}
