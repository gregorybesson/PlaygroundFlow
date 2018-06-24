<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\UserDomain;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class UserDomainFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new UserDomain($container);

        return $service;
    }
}
