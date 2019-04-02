<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\OpenGraphObject;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class OpenGraphObjectFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new OpenGraphObject($container);

        return $service;
    }
}
