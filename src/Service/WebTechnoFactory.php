<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\WebTechno;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class WebTechnoFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new WebTechno($container);

        return $service;
    }
}
