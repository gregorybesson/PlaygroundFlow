<?php
namespace PlaygroundFlow\Controller\Admin;

use PlaygroundFlow\Controller\Admin\StoryController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class StoryControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new StoryController($container);

        return $controller;
    }
}
