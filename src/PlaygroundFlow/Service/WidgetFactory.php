<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Widget;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WidgetFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundFlow\Service\Widget
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Widget($locator);

        return $service;
    }
}
