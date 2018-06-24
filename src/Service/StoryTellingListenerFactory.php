<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\StoryTellingListener;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class StoryTellingListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new StoryTellingListener($container);

        return $service;
    }
}
