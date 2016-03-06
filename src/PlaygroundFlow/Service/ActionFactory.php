<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Action;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ActionFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundFlow\Service\Action
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Action($locator);

        return $service;
    }
}
