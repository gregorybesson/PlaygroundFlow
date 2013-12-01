<?php

namespace PlaygroundFlow\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundFlow\Options\ModuleOptions;

class StoryTelling extends EventProvider implements ServiceManagerAwareInterface
{


    /**
     * @var EventMapperInterface
     */
    protected $storyTellingMapper;
    
    /**
     * @var StoryMappingMapperInterface
     */
    protected $storyMappingMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var EventServiceOptionsInterface
     */
    protected $options;
    
    public function tellStory($storyTelling)
    {
        // TODO : Put this mouth stuff to a dedicated listener.
        $userId = ($storyTelling->getProspect())? $storyTelling->getProspect()->getProspect():null;
        
        // TODO : apiKey is ... the key ! factorize it
        $args = array( 'apiKey' => 'key_first', 'userId' => $userId );
        $args["container"] = 'body';
         
        //TODO : Make it dynamic too ! (this has to be taken from the storyMapping's domain)
        $url = "http://localhost:93/notification";
        
        $placeholders = array('{username}','{points}', '{title}');
        $values = array($userId, $storyTelling->getPoints(), $storyTelling->getOpenGraphStoryMapping()->getStory()->getLabel());
        
        if($storyTelling->getOpenGraphStoryMapping()->getDisplayNotification()){
            
            $notification = str_replace($placeholders, $values, $storyTelling->getOpenGraphStoryMapping()->getNotification());
            if($storyTelling->getOpenGraphStoryMapping()->getWidget() && $storyTelling->getOpenGraphStoryMapping()->getWidget()->getAnchor()){
                $args["container"] = $storyTelling->getOpenGraphStoryMapping()->getWidget()->getAnchor(); //'#right-column';
            }
            if($storyTelling->getOpenGraphStoryMapping()->getWidget() && $storyTelling->getOpenGraphStoryMapping()->getWidget()->getTemplate()){
                $message = str_replace("{notification}", $notification, $storyTelling->getOpenGraphStoryMapping()->getWidget()->getTemplate());
            }else{
                $message = '<div id="pgActivityStream" class="playground" ><div >' .
                        '<a href="#" onclick="document.getElementById(\'pgActivityStream\').parentNode.removeChild(document.getElementById(\'pgActivityStream\'));">' .
                    'X</a>' .
                    $notification .
                    '</div></div>';
            }

            $args["who"]    = 'self';
            $args["style"]      = 'http://playground.local/lib/css/mouth.css';
            $args["html"]   = str_replace("=", "%3D", $message);
            
            $this->sendRequest($url, $args);
        }
        
        if($storyTelling->getOpenGraphStoryMapping()->getDisplayActivityStream()){
            $activityStream = str_replace($placeholders, $values, $storyTelling->getOpenGraphStoryMapping()->getActivityStream());
            
            $message = '<div id="pgActivityStream" class="playground" >' .
                    '<div >' .
                        '<a href="#" onclick="document.getElementById(\'pgActivityStream\').parentNode.removeChild(document.getElementById(\'pgActivityStream\'));">' .
                        'X</a>' .
                        $activityStream .
                    '</div>' .
                '</div>';
            
            $args["who"]        = 'others';
            $args["container"]  = 'body';
            $args["style"]      = 'http://playground.local/lib/css/mouth.css';
            
            $args["html"]       = str_replace("=", "%3D", $message);
    
            $this->sendRequest($url, $args);
        }
        
        return;
    }

    public function getStoryTellingMapper()
    {
        if (null === $this->storyTellingMapper) {
            $this->storyTellingMapper = $this->getServiceManager()->get('playgroundflow_storytelling_mapper');
        }

        return $this->storyTellingMapper;
    }
    
    /**
     * getStoryMappingMapper
     *
     * @return StoryMappingMapperInterface
     */
    public function getStoryMappingMapper()
    {
        if (null === $this->storyMappingMapper) {
            $this->storyMappingMapper = $this->getServiceManager()->get('playgroundflow_storyMapping_mapper');
        }
    
        return $this->storyMappingMapper;
    }
    
    /**
     * setStoryMappingMapper
     *
     * @param  StoryMappingMapperInterface $storyMappingMapper
     * @return StoryMapping
     */
    public function setStoryMappingMapper(StoryMappingMapperInterface $storyMappingMapper)
    {
        $this->storyMappingMapper = $storyMappingMapper;
    
        return $this;
    }

    public function setStoryTellingMapper($storyTellingMapper)
    {
        $this->storyTellingMapper = $storyTellingMapper;

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
     * @return Event
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
    
    /**
     * Actually send the to Mouth !
     *
     * @return void
     */
    public function sendRequest($url, $args)
    {
    
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => json_encode($args)
        );
        // print the array that was sent
        //echo "<pre>";
        //var_dump($args);
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
    }
}
