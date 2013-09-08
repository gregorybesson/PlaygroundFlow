<?php

namespace PlaygroundFlow\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundFlow\Options\ModuleOptions;

class Story extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var StoryMapperInterface
     */
    protected $storyMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var StoryServiceOptionsInterface
     */
    protected $options;

    public function create(array $data)
    {
    	$story  = new \PlaygroundFlow\Entity\OpenGraphStory();
    	$form  = $this->getServiceManager()->get('playgroundflow_story_form');
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
    	$form  = $this->getServiceManager()->get('playgroundflow_story_form');
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
            $this->storyMapper = $this->getServiceManager()->get('playgroundflow_story_mapper');
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
            $this->setOptions($this->getServiceManager()->get('playgroundflow_module_options'));
        }

        return $this->options;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param  ServiceManager $locator
     * @return Story
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
