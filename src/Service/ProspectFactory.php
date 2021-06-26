<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Prospect;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ProspectFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new Prospect($container);

        return $service;
    }
}
