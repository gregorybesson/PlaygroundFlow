<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Domain;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class DomainFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new Domain($container);

        return $service;
    }
}
