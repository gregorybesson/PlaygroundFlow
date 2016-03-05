<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\Story;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StoryFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundFlow\Service\Story
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Story($locator);

        return $service;
    }
}
