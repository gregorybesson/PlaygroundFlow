<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Object;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ObjectFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new Object($container);

        return $service;
    }
}
