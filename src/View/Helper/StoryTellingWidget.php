<?php

namespace PlaygroundFlow\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Interop\Container\ContainerInterface;

class StoryTellingWidget extends AbstractHelper
{
    /**
     * @var StoryTellingService
     */
    protected $storyTellingService;

    public function __construct(\PlaygroundFlow\Service\StoryTelling $storyTellingService) 
    {
        return $this->storyTellingService = $storyTellingService;
    }

    /**
     * __invoke
     *
     * @access public
     * @param  array  $options array of options
     * @return string
     */
    public function __invoke($user = null, $storyMappings = null)
    {
        $stories = [];
        if ($user) {
            if ($storyMappings === null) {
                $stories = $this->getStoryTellingService()->getStoryTellingMapper()->findBy(
                    ['user' => $user]
                );
            } else if (is_array($storyMappings)) {

            } else {
                $storyMapping = $this->getStoryTellingService()->getStoryMappingMapper()->findOneBy(
                    ['id' => $storyMappings]
                );

                if ($storyMapping) {
                    $stories = $this->getStoryTellingService()->getStoryTellingMapper()->findBy(
                        ['openGraphStoryMapping' => $storyMapping, 'user' => $user],
                        ['createdAt' => 'DESC']
                    );
                }
            }
        }

        return $stories;
    }

    /**
     * Get storyTellingService.
     *
     * @return StoryTellingService
     */
    public function getStoryTellingService()
    {
        return $this->storyTellingService;
    }
}
