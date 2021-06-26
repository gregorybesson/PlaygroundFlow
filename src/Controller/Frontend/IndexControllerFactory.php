<?php
namespace PlaygroundFlow\Controller\Frontend;

use PlaygroundFlow\Controller\Frontend\IndexController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new IndexController($container);

        return $controller;
    }
}
