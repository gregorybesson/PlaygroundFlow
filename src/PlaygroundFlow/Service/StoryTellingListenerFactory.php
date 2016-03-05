<?php
namespace PlaygroundFlow\Service;

use PlaygroundFlow\Service\StoryTellingListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StoryTellingListenerFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundFlow\Service\StoryTellingListener
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new StoryTellingListener($locator);

        return $service;
    }
}
