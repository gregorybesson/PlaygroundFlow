<?php
namespace PlaygroundFlow\Controller\Admin;

use PlaygroundFlow\Controller\Admin\WebTechnoController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class WebTechnoControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new WebTechnoController($container);

        return $controller;
    }
}
