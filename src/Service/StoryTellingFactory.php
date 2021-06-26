<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\StoryTelling;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class StoryTellingFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new StoryTelling($container);

        return $service;
    }
}
