<?php
namespace PlaygroundFlow\View\Helper;

use PlaygroundFlow\View\Helper\StoryTellingWidget;
use Interop\Container\ContainerInterface;

class StoryTellingWidgetFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container)
    {
        $service = $container->get('playgroundflow_storytelling_service');
        return new StoryTellingWidget($service);
    }
}