<?php
namespace PlaygroundFlow\Controller\Frontend;

use PlaygroundFlow\Controller\Frontend\EasyXDMController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class EasyXDMControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new EasyXDMController($container);

        return $controller;
    }
}
