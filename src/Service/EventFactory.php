<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Event;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class EventFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new Event($container);

        return $service;
    }
}
