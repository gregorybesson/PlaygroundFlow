<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Domain;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DomainFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundFlow\Service\Domain
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Domain($locator);

        return $service;
    }
}
