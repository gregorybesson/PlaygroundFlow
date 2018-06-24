<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Story;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class StoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new Story($container);

        return $service;
    }
}
