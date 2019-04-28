<?php
namespace PlaygroundFlow\Service;

use Zend\Session\Container;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager;

/**
 * This listener is used to gather the stories from the managed domains
 *
 * @author Gregory Besson <gregory.besson@playground.gg>
 */
class StoryTellingListener implements ListenerAggregateInterface
{
    use EventManagerAwareTrait;

    /**
     *
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    protected $eventsArray = array();

    protected $leaderboardService;

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

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        //Creating the Pre-events
        $sm = $this->serviceLocator;
        
        $app = $sm->get('Application');
        $uri = $app->getRequest()->getUri();
        $domainId = $uri->getHost();

        $domainService = $sm->get('playgroundflow_domain_service');
        $storyTellingService = $sm->get('playgroundflow_storytelling_service');
        
        $domain = $domainService->getDomainMapper()->findOneBy(['domain'=>$domainId]);
        
        $storymappings = $storyTellingService->getStoryMappingMapper()->findBy(
            ['domain' => $domain]
        );

        // We deduplicate the events "$storyMapping->getEventBeforeUrl()" and "$storyMapping->getEventAfterUrl()"
        // So that we trigger this event only once
        $storyBefore = [];
        $storyAfter = [];
        foreach ($storymappings as $storyMapping) {
            if ($storyMapping->getEventBeforeUrl()
                && !in_array($storyMapping->getEventBeforeUrl(), $storyBefore)
            ) {
                $this->listeners[] = $events->getSharedManager()->attach(
                    '*',
                    $storyMapping->getEventBeforeUrl(),
                    [$this, 'tellStoryBefore'],
                    100
                );
                $storyBefore[] = $storyMapping->getEventBeforeUrl();
            }
            
            if ($storyMapping->getEventAfterUrl()
                && !in_array($storyMapping->getEventAfterUrl(), $storyAfter)
            ) {
                $this->listeners[] = $events->getSharedManager()->attach(
                    '*',
                    $storyMapping->getEventAfterUrl(),
                    [$this, 'tellStoryAfter'],
                    100
                );
                $storyAfter[] = $storyMapping->getEventAfterUrl();
            }
        }
        // This event can be triggered to add or remove points from the leaderboard 
        // $this->listeners[] = $events->getSharedManager()->attach(
        //     '*',
        //     'leaderboardUserUpdate',
        //     [
        //         $this,
        //         'leaderboardUserUpdate'
        //     ],
        //     100
        // );
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
                $storyTelling->setOpenGraphStoryMapping($story)
                    ->setUser($sponsorStory->getUser())
                    ->setObject(json_encode($object))
                    ->setPoints($story->getPoints());
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
                $storyTelling->setOpenGraphStoryMapping($story)
                    ->setUser($user)
                    ->setObject(json_encode($object))
                    ->setPoints($story->getPoints());
                $storyTellingService->getStoryTellingMapper()->insert($storyTelling);
            }
        }
        $this->eventsArray[$e->getName()] = false;
    }
    
    public function tellStoryBefore(\Zend\EventManager\Event $e)
    {
        $data = $e->getParam('data');
        $user = $e->getParam('user');
        $secretKey = $e->getParam('secretKey');
        
        $sm = $e->getTarget()->getServiceManager();
        $storyTellingService = $sm->get('playgroundflow_storytelling_service');
        
        // I reset the array before anything
        $this->eventsArray[$e->getName()] = null;
        
        $stories = $storyTellingService->getStoryMappingMapper()->findBy(array(
            'eventBeforeUrl' => $e->getName()
        ));
        
        foreach ($stories as $storyMapping) {
            $objectArray = array();
            foreach ($storyMapping->getObjects() as $objectMapping) {
                $objectCode = $objectMapping->getObject()->getCode();
                $instance = $e->getParam($objectCode);
                foreach ($objectMapping->getAttributes() as $attributeMapping) {
                    //echo "object : " . $objectMapping->getObject()->getCode() . "<br>";
                    //echo "object id : " . $objectMapping->getObject()->getId() . "<br>";
                    //echo "attribut : " . $attributeMapping->getAttribute()->getCode() . "<br>";
                    if (method_exists($instance, $method = ('get' . ucfirst($attributeMapping->getAttribute()->getCode())))) {
                        if (isset($data[$attributeMapping->getAttribute()->getCode()]) && $instance->$method() != $data[$attributeMapping->getAttribute()->getCode()]) {
                            $this->eventsArray[$e->getName()]['before'][$objectCode][$attributeMapping->getAttribute()->getCode()] = $instance->$method();
                            $this->eventsArray[$e->getName()]['after'][$objectCode][$attributeMapping->getAttribute()->getCode()] = $data[$attributeMapping->getAttribute()->getCode()];
                        }
                    }
                }
            }
        }
    }
    
    /**
     *
     * @param Event $e
     */
    public function tellStoryAfter(\Zend\EventManager\Event $e)
    {
        $user       = $e->getParam('user');
        $prospect   = $e->getParam('prospect');
        $secretKey  = $e->getParam('secretKey');
        
        $sm         = $e->getTarget()->getServiceManager();
        
        $app = $sm->get('Application');
        $uri = $app->getRequest()->getUri();
        $domainId = $uri->getHost();
        
        $domainService = $sm->get('playgroundflow_domain_service');
        $storyTellingService = $sm->get('playgroundflow_storytelling_service');
        
        $domain = $domainService->getDomainMapper()->findOneBy(array('domain'=>$domainId));
    
        // If the secretKey is not empty, I search the user associated with it as I want him to live the story
        if (!empty($secretKey)) {
            $sponsorStory = $storyTellingService->getStoryTellingMapper()->findOneBySecretKey($secretKey);
            if ($sponsorStory) {
                $user = $sponsorStory->getUser();
                $prospect = null;
            }
        }
        
        $stories = $storyTellingService->getStoryMappingMapper()->findBy(
            [
                'domain' => $domain,
                'eventAfterUrl' => $e->getName()
            ]
        );
        foreach ($stories as $storyMapping) {
            $objectArray = array();
            $createStoryTelling = true;
            // an event before has been triggered
            $key = $storyMapping->getEventBeforeUrl();
            $limitNumber = $storyMapping->getCountLimit();

            if (!empty($key) && isset($this->eventsArray[$key]) && $this->eventsArray[$key] !== null) {
                $objectArray = $this->eventsArray[$key];
            } else {
                foreach ($storyMapping->getObjects() as $objectMapping) {
                    $objectCode = $e->getParam($objectMapping->getObject()->getCode());
                    if (is_string($objectCode) || is_numeric($objectCode)) {
                        $objectArray[$objectMapping->getObject()->getCode()] = $objectCode;
                    } else {
                        foreach ($objectMapping->getAttributes() as $attributeMapping) {
                            // echo "object : " . $objectMapping->getObject()->getCode() . "<br>";
                            // echo "object id : " . $objectMapping->getObject()->getId() . "<br>";
                            // echo "attribut : " . $attributeMapping->getAttribute()->getCode() . "<br>";
                            if (method_exists($objectCode, $method = ('get' . ucfirst($attributeMapping->getAttribute()->getCode())))) {
                                $result = $objectCode->$method();

                                $operator = $attributeMapping->getComparison();
                                $overloadPoints = $attributeMapping->getOverloadPoints();

                                /*  If the attribute type is an array of objects and the admin a set an attribute in attributeArray
                                 *    Then I get the array of this attribute as the $result
                                 */
                                if ($attributeMapping->getAttribute()->getType() == 'array' && !empty($attributeMapping->getAttributeArray())) {
                                    $ar = [];
                                    foreach ($result as $o) {
                                        if (method_exists($o, $method = ('get' . ucfirst($attributeMapping->getAttributeArray()->getCode())))) {
                                            $ar[] = $o->$method();
                                        }
                                    }
                                    $result = $ar;
                                }

                                $objectArray[$objectMapping->getObject()->getCode()][$attributeMapping->getAttribute()->getCode()] = $result;

                                if ($operator !== null
                                    && $operator !== ''
                                    && (!$this->$operator($result, $attributeMapping->getValue()))
                                ) {
                                    $createStoryTelling = false;
                                }

                                if ($overloadPoints) {
                                    $storyMapping->setPoints($result);
                                }

                                // echo "resultat de l'object reflexion:";
                                // print_r($objectCode->$method());
                            }
                        }
                    }
                }
            }

            // let's check if the limit of this storytelling has not been reached
            if ($limitNumber > 0 && $createStoryTelling) {
                $stories = $storyTellingService->getStoryTellingMapper()->findBy(
                    [
                        'openGraphStoryMapping' => $storyMapping,
                        'user' => $user
                    ]
                );
                if (count($stories) >= $limitNumber) {
                    $createStoryTelling = false;
                }
            }

            if ($createStoryTelling) {
                $storyTelling = new \PlaygroundFlow\Entity\OpenGraphStoryTelling();
                $storyTelling->setOpenGraphStoryMapping($storyMapping)
                    ->setUser($user)
                    ->setProspect($prospect)
                    ->setObject(json_encode($objectArray))
                    ->setPoints($storyMapping->getPoints())
                    ->setSecretKey($secretKey);
                $storyTellingService->getStoryTellingMapper()->insert($storyTelling);

                $storyTellingService->tellStory($storyTelling);
                $this->getLeaderboardService()->addPoints($storyMapping, $user);

                $e->getTarget()->getEventManager()->trigger('story.'.$storyMapping->getId(), $this, array('storyTelling' => $storyTelling));
            }
        }
    }

    /**
     * This method updates the default leaderboard of a user
     *
     * @param  EventManager $e
     * @return array
     */
    public function leaderboardUserUpdate($e)
    {
        // $user = $e->getParam('user');
        // $game = $e->getParam('game');
        // $entry = $e->getParam('entry');
        // $points = $e->getParam('points');
        // $sm = $e->getTarget()->getServiceManager();

        // $objectArray = [];
        // $objectArray['game'] = ['title' => $game->getTitle(), 'identifier' => $game->getIdentifier()];
        // $objectArray['entry'] = ['id' => $entry->getId(), 'paidAmount' => $entry->getPaidAmount()];
        
        // $storyTellingService = $sm->get('playgroundflow_storytelling_service');
        // $storyTelling = new \PlaygroundFlow\Entity\OpenGraphStoryTelling();
        // $storyTelling->setOpenGraphStoryMapping(null)
        //     ->setUser($user)
        //     ->setObject(json_encode($objectArray))
        //     ->setPoints($entry->getPaidAmount());
        // $storyTellingService->getStoryTellingMapper()->insert($storyTelling);

        // //$storyTellingService->tellStory($storyTelling);

        // $leaderboardType = $this->getLeaderboardService()->getLeaderboardTypeService()->getLeaderboardTypeDefault();
        // $userPoints = $this->getLeaderboardService()->add($points, $user, $leaderboardType);

        // $e->getTarget()->getEventManager()->trigger('leaderboardUSerUpdate.post', $this, array('storyTelling' => $storyTelling));

        // return $userPoints;
    }

    public function empty($op1, $op2)
    {
        return empty($op1);
    }

    public function not_empty($op1, $op2)
    {
        return !empty($op1);
    }

    public function contains($op1, $op2)
    {
        if (is_array($op1)) {
            return in_array($op2, $op1);
        }
        
        return false;
    }

    public function does_not_contain($op1, $op2)
    {
        if (is_array($op1)) {
            return !in_array($op2, $op1);
        }
        
        return false;
    }

    public function equals($op1, $op2)
    {
        return $op1 == $op2;
    }

    public function more_than($op1, $op2)
    {
        return $op1 >= $op2;
    }

    public function less_than($op1, $op2)
    {
        return $op1 <= $op2;
    }

     /**
     * Retrieve service Leaderboard
     *
     * @return Service/Leaderboard leaderboardService
     */
    public function getLeaderboardService()
    {
        if (! $this->leaderboardService) {
            $this->leaderboardService = $this->serviceLocator->get('playgroundreward_leaderboard_service');
        }
    
        return $this->leaderboardService;
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

    public function getServiceManager()
    {
        return $this->serviceLocator;
    }
}
