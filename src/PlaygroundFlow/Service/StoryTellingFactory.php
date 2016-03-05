<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\StoryTelling;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StoryTellingFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundFlow\Service\StoryTelling
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new StoryTelling($locator);

        return $service;
    }
}
