<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\UserDomain;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserDomainFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundFlow\Service\UserDomain
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new UserDomain($locator);

        return $service;
    }
}
