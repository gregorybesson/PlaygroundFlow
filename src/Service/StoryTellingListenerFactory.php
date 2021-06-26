<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\StoryTellingListener;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class StoryTellingListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new StoryTellingListener($container);

        return $service;
    }
}
