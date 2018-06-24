<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Widget;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class WidgetFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new Widget($container);

        return $service;
    }
}
