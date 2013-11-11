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
        //$action = $data["story_mapping_id"];
         
        //TODO : Make it dynamic
        //$args["style"] = 'http://playground.local/lib/css/mouth.css';
        $args["container"] = '#col-droite';
        //TODO : Make it dynamic too ! (this has to be taken from the storyMapping's domain)
        $url = "http://localhost:93/notification";
         
        $welcome = 
            '<div id="chrono">' .
                '<div class="header" >' .
                    '<h2> '.$storyTelling->getPoints().' points </h2>' .
                    '<p style="color:#fff;line-height:46px"> (' . $storyTelling->getOpenGraphStoryMapping()->getStory()->getLabel() .')</p>' .
                '</div>' .
            '</div>';
         
        $login ='<div id="welcome" class="playground" >' .
            '<div >' .
            '<a ' .
            'href="#" ' .
            'onclick="document.getElementById(\'welcome\').parentNode.removeChild(document.getElementById(\'welcome\'));" ' .
            '>X</a>' .
            'Welcome aboard ! Ready to hunt ?' .
            '</div>' .
            '</div>';
         
        // html for other user that the one that just logged off
        $bye = '<div id="bye" class="playground" >' .
            '<div >' .
            '<a ' .
            'href="#" ' .
            'onclick="document.getElementById(\'bye\').parentNode.removeChild(document.getElementById(\'bye\'));" ' .
            '>X</a>' .
            'User ' . $userId . ' has won ' . $storyTelling->getPoints() . ' points for the story "' . $storyTelling->getOpenGraphStoryMapping()->getStory()->getLabel() . '"' .
            '</div>' .
            '</div>';
         
        // html for user that found the treasure
        $win = '<div id="win" class="playground" >' .
            '<div >' .
            '<a ' .
            'href="#" ' .
            'onclick="document.getElementById(\'win\').parentNode.removeChild(document.getElementById(\'win\'));" ' .
            '>X</a>' .
            'Congratz ! You have found the treasure ! : ' .
            '</div>' .
            '</div>';
         
        // html for other user that loose and didn't find the treasure
        $loose = '<div id="loose" class="playground" >' .
            '<div >' .
            '<a ' .
            'href="#" ' .
            'onclick="document.getElementById(\'loose\').parentNode.removeChild(document.getElementById(\'loose\'));" ' .
            '>X</a>' .
            'User ' . $userId . ' has found the secret treasure' .
            '</div>' .
            '</div>';
         
        $args["who"]    = 'self';
        $args["html"]   = str_replace("=", "%3D", $welcome);

        $this->sendRequest($url, $args);
        
        $args["who"]        = 'others';
        $args["style"]      = 'http://playground.local/lib/css/mouth.css';
        $args["container"]  = 'body';
        $args["html"]       = str_replace("=", "%3D", $bye);

        $this->sendRequest($url, $args);
        
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
