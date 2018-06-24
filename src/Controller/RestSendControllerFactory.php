<?php
namespace PlaygroundFlow\Controller;

use PlaygroundFlow\Controller\RestSendController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class RestSendControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new RestSendController($container);

        return $controller;
    }
}
