<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Widget;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class WidgetFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new Widget($container);

        return $service;
    }
}
