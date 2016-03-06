<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Prospect;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProspectFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundFlow\Service\Prospect
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Prospect($locator);

        return $service;
    }
}
