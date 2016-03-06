<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\WebTechno;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WebTechnoFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundFlow\Service\WebTechno
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new WebTechno($locator);

        return $service;
    }
}
