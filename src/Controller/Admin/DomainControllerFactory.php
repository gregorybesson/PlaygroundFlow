<?php
namespace PlaygroundFlow\Controller\Admin;

use PlaygroundFlow\Controller\Admin\DomainController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class DomainControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new DomainController($container);

        return $controller;
    }
}
