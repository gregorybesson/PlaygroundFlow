<?php
namespace PlaygroundFlow\Service;

use Zend\Session\Container;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\Event;
use ZfcBase\EventManager\EventProvider;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * This listener is used to gather the stories from the managed domains
 *
 * @author Gregory Besson <gregory.besson@playground.gg>
 */
class StoryTellingListener extends EventProvider implements ListenerAggregateInterface, ServiceManagerAwareInterface
{

    /**
     *
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    protected $eventsArray = array();
    
    protected $serviceManager;

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        
        // PLAY A GAME
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), 'play.post', array(
            $this,
            'tellStoryAfter'
        ), 200);
        
        // GOOD ANSWERS ON A QUIZ
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), 'createQuizReply.post', array(
            $this,
            'tellStoryAfter'
        ), 200);
        
        // SHARE BY MAIL
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), 'sendShareMail.post', array(
            $this,
            'tellStoryAfter'
        ), 200);
        
        // SHARE ON FB WALL
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), 'postFbWall.post', array(
            $this,
            'tellStoryAfter'
        ), 200);
        
        // SHARE ON TWITTER
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), 'postTwitter.post', array(
            $this,
            'tellStoryAfter'
        ), 200);
        
        // SHARE ON GOOGLE
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), 'postGoogle.post', array(
            $this,
            'tellStoryAfter'
        ), 200);
        
        // REGISTER
        $this->listeners[] = $events->getSharedManager()->attach('PlaygroundUser\Service\User', 'register.post', array(
            $this,
            'tellStoryAfter'
        ), 200);
        
        // REGISTRATION SPONSORING
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), 'sponsor.post', array(
            $this,
            'sponsorAfter'
        ), 200);
        
        // OPTIN
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), 'updateNewsletter.pre', array(
            $this,
            'newsletterBefore'
        ), 200);
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), 'updateNewsletter.post', array(
            $this,
            'newsletterAfter'
        ), 200);
        
        // OPTINPARTNER
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), 'updateNewsletterPartner.pre', array(
            $this,
            'newsletterBefore'
        ), 201);
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), 'updateNewsletterPartner.post', array(
            $this,
            'newsletterAfter'
        ), 201);
        
        // UPDATE ACCOUNT INFO
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), array(
            'updateInfo.pre'
        ), array(
            $this,
            'infoBefore'
        ), 200);
        $this->listeners[] = $events->getSharedManager()->attach(array(
            '*'
        ), array(
            'updateInfo.post'
        ), array(
            $this,
            'infoAfter'
        ), 200);
        
    }

    /**
     * {@inheritDoc}
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     *
     * @param Event $e            
     */
    public function tellStoryAfter(\Zend\EventManager\Event $e)
    {
        $user = $e->getParam('user');
        $secretKey = $e->getParam('secretKey');
        
        $sm = $e->getTarget()->getServiceManager();
        $storyTellingService = $sm->get('playgroundflow_storytelling_service');
        
        $stories = $storyTellingService->getStoryMappingMapper()->findBy(array(
            'eventAfterUrl' => $e->getName()
        ));
        foreach ($stories as $storyMapping) {
            $objectArray = array();
            foreach($storyMapping->getObjects() as $objectMapping){
                $objectCode = $e->getParam($objectMapping->getObject()->getCode());
                foreach($objectMapping->getAttributes() as $attributeMapping){
                    //echo "object : " . $objectMapping->getObject()->getCode() . "<br>";
                    //echo "object id : " . $objectMapping->getObject()->getId() . "<br>";
                    //echo "attribut : " . $attributeMapping->getAttribute()->getCode() . "<br>";
                    if( method_exists( $objectCode , $method = ( 'get' . ucfirst( $attributeMapping->getAttribute()->getCode() ) ) ) ){
                    $objectArray[$objectMapping->getObject()->getCode()][$attributeMapping->getAttribute()->getCode()] = $objectCode->$method();
                    }                    
                }
            }
            
            $storyTelling = new \PlaygroundFlow\Entity\OpenGraphStoryTelling();
            $storyTelling->setOpenGraphStoryMapping($storyMapping);
            $storyTelling->setUser($user);
            $storyTelling->setObject(json_encode($objectArray));
            $storyTelling->setPoints($storyMapping->getPoints());
            $storyTelling->setSecretKey($secretKey);
            $storyTellingService->getStoryTellingMapper()->insert($storyTelling);
            
            $e->getTarget()->getEventManager()->trigger('story.'.$storyMapping->getId() , $this, array('storyTelling' => $storyTelling));
        }
    }

    /**
     * differences = correctAnswers + game + winner
     * @param \Zend\EventManager\Event $e
     */
    public function createQuizReplyAfter(\Zend\EventManager\Event $e)
    {
        $correctAnswers = $e->getParam('correctAnswers');
        $winner = $e->getParam('winner');
        $game = $e->getParam('game');
        $user = $e->getParam('user');
        
        $sm = $e->getTarget()->getServiceManager();
        $storyTellingService = $sm->get('playgroundflow_storytelling_service');
        
        $stories = $storyTellingService->getStoryMappingMapper()->findBy(array(
            'eventAfterUrl' => $e->getName()
        ));
        foreach ($stories as $story) {
            $object = array();
            $object['game'] = array(
                'id' => $game->getId(),
                'type' => $game->getClassType()
            );
            $object['winner'] = $winner;
            $object['correctAnswers'] = $correctAnswers;
            
            $storyTelling = new \PlaygroundFlow\Entity\OpenGraphStoryTelling();
            $storyTelling->setOpenGraphStoryMapping($story);
            $storyTelling->setUser($user);
            $storyTelling->setObject(json_encode($object));
            $storyTelling->setPoints($story->getPoints());
            $storyTellingService->getStoryTellingMapper()->insert($storyTelling);
        }
    }

    /**
     * differences :  RECHERCHE DE 1 OCCURENCE EXISTENTE
     * @param \Zend\EventManager\Event $e
     */
    public function sponsorAfter(\Zend\EventManager\Event $e)
    {
        $user = $e->getParam('user');
        $secretKey = $e->getParam('secretKey');
        
        $sm = $e->getTarget()->getServiceManager();
        $storyTellingService = $sm->get('playgroundflow_storytelling_service');
        
        $sponsorStory = $storyTellingService->getStoryTellingMapper()->findOneBySecretKey($secretKey);
        
        if ($sponsorStory) {
            $stories = $storyTellingService->getStoryMappingMapper()->findBy(array(
                'eventAfterUrl' => $e->getName()
            ));
            foreach ($stories as $story) {
                $object = array();
                $object['user'] = array(
                    'id' => $user->getId(),
                    'email' => $user->getEmail()
                );
                
                $storyTelling = new \PlaygroundFlow\Entity\OpenGraphStoryTelling();
                $storyTelling->setOpenGraphStoryMapping($story);
                $storyTelling->setUser($sponsorStory->getUser());
                $storyTelling->setObject(json_encode($object));
                $storyTelling->setPoints($story->getPoints());
                $storyTellingService->getStoryTellingMapper()->insert($storyTelling);
            }
        }
    }

    /**
     * differences : BEFORE !! et data et calcul
     * @param \Zend\EventManager\Event $e
     */
    public function newsletterBefore(\Zend\EventManager\Event $e)
    {
        $data = $e->getParam('data');
        $user = $e->getParam('user');
        
        $sm = $e->getTarget()->getServiceManager();
        $storyTellingService = $sm->get('playgroundflow_storytelling_service');
        /*
         * $logText = 'optin avant : ' . $user->getOptin() . 'apres : ' . $data['optin'] . '<br/>'; $logText .= 'optinPartner avant : ' . $user->getOptinPartner() . 'apres : ' . $data['optinPartner']. '<br/>'; $sm->get('jhu.zdt_logger')->info($logText);
         */
        
        // si avant !=1 et apres=1 => true
        if ($user->getOptin() != 1 && $data['optin'] == 1 && $e->getName() == 'updateNewsletter.pre') {
            $storiesMapping = $storyTellingService->getStoryMappingMapper()->findBy(array(
                'eventAfterUrl' => 'updateNewsletter.post'
            ));
            $nbStories = 0;
            foreach ($storiesMapping as $storyMapping) {
                $stories = $storyTellingService->getStoryTellingMapper()->findBy(array(
                    'openGraphStoryMapping' => $storyMapping,
                    'user' => $user
                ));
                $nbStories += count($stories);
            }
            
            if ($nbStories == 0) {
                $this->eventsArray['updateNewsletter.post'] = true;
            }
        }
        
        if ($user->getOptinPartner() != 1 && $data['optinPartner'] == 1 && $e->getName() == 'updateNewsletterPartner.pre') {
            $storiesMapping = $storyTellingService->getStoryMappingMapper()->findBy(array(
                'eventAfterUrl' => 'updateNewsletterPartner.post'
            ));
            $nbStories = 0;
            foreach ($storiesMapping as $storyMapping) {
                $stories = $storyTellingService->getStoryTellingMapper()->findBy(array(
                    'openGraphStoryMapping' => $storyMapping,
                    'user' => $user
                ));
                $nbStories += count($stories);
            }
            if ($nbStories == 0) {
                $this->eventsArray['updateNewsletterPartner.post'] = true;
            }
        }
    }

    /**
     * differences : je parcours une var en memoire
     * @param \Zend\EventManager\Event $e
     */
    public function newsletterAfter(\Zend\EventManager\Event $e)
    {
        $user = $e->getParam('user');
        $sm = $e->getTarget()->getServiceManager();
        $storyTellingService = $sm->get('playgroundflow_storytelling_service');
        
        if (isset($this->eventsArray[$e->getName()]) && $this->eventsArray[$e->getName()] === true) {
            // On compte les events
            $stories = $storyTellingService->getStoryMappingMapper()->findBy(array(
                'eventAfterUrl' => $e->getName()
            ));
            foreach ($stories as $story) {
                $object = array();
                $object['user'] = array(
                    'id' => $user->getId(),
                    'email' => $user->getEmail()
                );
                $storyTelling = new \PlaygroundFlow\Entity\OpenGraphStoryTelling();
                $storyTelling->setOpenGraphStoryMapping($story);
                $storyTelling->setUser($user);
                $storyTelling->setObject(json_encode($object));
                $storyTelling->setPoints($story->getPoints());
                $storyTellingService->getStoryTellingMapper()->insert($storyTelling);
            }
        }
        $this->eventsArray[$e->getName()] = false;
    }

    /**
     * differences : BEFORE
     * @param \Zend\EventManager\Event $e
     */
    public function infoBefore(\Zend\EventManager\Event $e)
    {
        $data = $e->getParam('data');
        $user = $e->getParam('user');
        
        $sm = $e->getTarget()->getServiceManager();
        $storyTellingService = $sm->get('playgroundflow_storytelling_service');
        
        if (isset($data['username']) && $user->getUsername() != $data['username'] && $data['username'] != '') {
            $this->eventsArray['updateInfo.post']['before']['username'] = $user->getUsername();
            $this->eventsArray['updateInfo.post']['after']['username'] = $data['username'];
        }
        if (isset($data['avatar']) && $user->getAvatar() != $data['avatar'] && $data['avatar'] != '') {
            $this->eventsArray['updateInfo.post']['before']['avatar'] = $user->getAvatar();
            $this->eventsArray['updateInfo.post']['after']['avatar'] = $data['avatar'];
        }
        if (isset($data['address']) && $user->getAddress() != $data['address'] && $data['address'] != '') {
            $this->eventsArray['updateInfo.post']['before']['address'] = $user->getAddress();
            $this->eventsArray['updateInfo.post']['after']['address'] = $data['address'];
        }
        if (isset($data['city']) && $user->getCity() != $data['city'] && $data['city'] != '') {
            $this->eventsArray['updateInfo.post']['before']['city'] = $user->getCity();
            $this->eventsArray['updateInfo.post']['after']['city'] = $data['city'];
        }
        if (isset($data['telephone']) && $user->getTelephone() != $data['telephone'] && $data['telephone'] != '') {
            $this->eventsArray['updateInfo.post']['before']['telephone'] = $user->getTelephone();
            $this->eventsArray['updateInfo.post']['after']['telephone'] = $data['telephone'];
        }
    }

    /**
     * differences : parcours var
     * @param \Zend\EventManager\Event $e
     */
    public function infoAfter(\Zend\EventManager\Event $e)
    {
        $user = $e->getParam('user');
        $sm = $e->getTarget()->getServiceManager();
        $storyTellingService = $sm->get('playgroundflow_storytelling_service');
        
        if (isset($this->eventsArray[$e->getName()]) && $this->eventsArray[$e->getName()] !== null) {
            
            $stories = $storyTellingService->getStoryMappingMapper()->findBy(array(
                'eventAfterUrl' => $e->getName()
            ));
            foreach ($stories as $story) {
                $object = $this->eventsArray[$e->getName()];
                $storyTelling = new \PlaygroundFlow\Entity\OpenGraphStoryTelling();
                $storyTelling->setOpenGraphStoryMapping($story);
                $storyTelling->setUser($user);
                $storyTelling->setObject(json_encode($object));
                $storyTelling->setPoints($story->getPoints());
                $storyTellingService->getStoryTellingMapper()->insert($storyTelling);
            }
        }
        $this->eventsArray[$e->getName()] = null;
    }
    
    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager ()
    {
        return $this->serviceManager;
    }
    
    /**
     * Set service manager instance
     *
     * @param  ServiceManager $sm
     * @return User
     */
    public function setServiceManager (ServiceManager $sm)
    {
        $this->serviceManager = $sm;
    
        return $this;
    }
}
