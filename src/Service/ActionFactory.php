<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Action;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ActionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null
    {
        $service = new Action($container);

        return $service;
    }
}
