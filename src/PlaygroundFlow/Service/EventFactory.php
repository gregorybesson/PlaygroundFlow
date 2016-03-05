<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Event;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundFlow\Service\Event
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Event($locator);

        return $service;
    }
}
