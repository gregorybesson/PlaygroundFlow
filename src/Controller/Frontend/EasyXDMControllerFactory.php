<?php
namespace PlaygroundFlow\Controller\Admin;

use PlaygroundFlow\Controller\Admin\ActionController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ActionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new ActionController($container);

        return $controller;
    }
}
