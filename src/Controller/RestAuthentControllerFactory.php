<?php
namespace PlaygroundFlow\Controller;

use PlaygroundFlow\Controller\RestAuthentController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class RestAuthentControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new RestAuthentController($container);

        return $controller;
    }
}
