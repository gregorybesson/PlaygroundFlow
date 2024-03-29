<?php

namespace PlaygroundFlow\Service;

use Laminas\ServiceManager\ServiceManager;
use Laminas\EventManager\EventManagerAwareTrait;
use PlaygroundFlow\Options\ModuleOptions;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\EventManager\EventManager;

class Story
{
    use EventManagerAwareTrait;

    /**
     * @var StoryMapperInterface
     */
    protected $storyMapper;

    /**
     * @var StoryServiceOptionsInterface
     */
    protected $options;

    /**
     *
     * @var ServiceManager
     */
    protected $serviceLocator;

    protected $event;

    public function __construct(ServiceLocatorInterface $locator)
    {
        $this->serviceLocator = $locator;
    }

    public function create(array $data)
    {
        $story  = new \PlaygroundFlow\Entity\OpenGraphStory();
        $form  = $this->serviceLocator->get('playgroundflow_story_form');
        $form->bind($story);
        $form->setData($data);
        
        if (!$form->isValid()) {
            return false;
        }
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('story' => $story, 'data' => $data));
        $this->getStoryMapper()->insert($story);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('story' => $story, 'data' => $data));
        
        return $story;
    }

    public function edit(array $data, $story)
    {
        $form  = $this->serviceLocator->get('playgroundflow_story_form');
        $form->bind($story);
        $form->setData($data);
         
        if (!$form->isValid()) {
            return false;
        }
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('story' => $story, 'data' => $data));
        $this->getStoryMapper()->update($story);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('story' => $story, 'data' => $data));

        return $story;
    }

    /**
     * getStoryMapper
     *
     * @return StoryMapperInterface
     */
    public function getStoryMapper()
    {
        if (null === $this->storyMapper) {
            $this->storyMapper = $this->serviceLocator->get('playgroundflow_story_mapper');
        }

        return $this->storyMapper;
    }

    /**
     * setStoryMapper
     *
     * @param  StoryMapperInterface $storyMapper
     * @return Story
     */
    public function setStoryMapper(StoryMapperInterface $storyMapper)
    {
        $this->storyMapper = $storyMapper;

        return $this;
    }

    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->serviceLocator->get('playgroundflow_module_options'));
        }

        return $this->options;
    }

    public function getEventManager()
    {
        if ($this->event === NULL) {
            $this->event = new EventManager(
                $this->serviceLocator->get('SharedEventManager'), [get_class($this)]
            );
        }
        return $this->event;
    }
}
