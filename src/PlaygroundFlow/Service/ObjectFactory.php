<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Object;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ObjectFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundFlow\Service\Object
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Object($locator);

        return $service;
    }
}
