<?php

namespace PlaygroundFlow\Service;

use Laminas\ServiceManager\ServiceManager;
use Laminas\EventManager\EventManagerAwareTrait;
use PlaygroundFlow\Options\ModuleOptions;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\EventManager\EventManager;

class Event
{
    use EventManagerAwareTrait;

    /**
     * @var EventMapperInterface
     */
    protected $eventMapper;

    /**
     * @var EventServiceOptionsInterface
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

    public function edit(array $data, $event)
    {
        $this->getEventMapper()->update($event);
        //$this->getEventManager()->trigger(__FUNCTION__, $this, array('event' => $event, 'data' => $data));
        //$this->getEventMapper()->insert($event);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('event' => $event, 'data' => $data));

        return $event;
    }

    /**
     * This function return count of events or total points by event category for one user
     * @param unknown_type $user
     * @param unknown_type $type
     * @param unknown_type $count
     */
    public function getTotal($user, $type = '', $count = 'points')
    {
        $em = $this->serviceLocator->get('playgroundflow_doctrine_em');

        if ($count == 'points') {
            $aggregate = 'SUM(e.points)';
        } elseif ($count == 'count') {
            $aggregate = 'COUNT(e.id)';
        }

        switch ($type) {
            case 'game':
                $filter = array(12);
                break;
            case 'user':
                $filter = array(1,4,5,6,7,8,9,10,11);
                break;
            case 'newsletter':
                $filter = array(2,3);
                break;
            case 'sponsorship':
                $filter = array(20);
                break;
            case 'social':
                $filter = array(13,14,15,16,17);
                break;
            case 'quizAnswer':
                $filter = array(30);
                break;
            case 'badgesBronze':
                $filter = array(100);
                break;
            case 'badgesSilver':
                $filter = array(101);
                break;
            case 'badgesGold':
                $filter = array(102);
                break;
            case 'anniversary':
                $filter = array(25);
                break;
            default:
                $filter = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,100,101,102,103);
        }

        $query = $em->createQuery('SELECT ' . $aggregate . ' FROM PlaygroundFlow\Entity\Event e WHERE e.user = :user AND e.actionId in (?1)');
        $query->setParameter('user', $user);
        $query->setParameter(1, $filter);
        $total = $query->getSingleScalarResult();

        return $total;
    }

    public function findBy($array, $sort = array())
    {
        return $this->getEventMapper()->findBy($array, $sort = array());
    }

    /**
     * getEventMapper
     *
     * @return EventMapperInterface
     */
    public function getEventMapper()
    {
        if (null === $this->eventMapper) {
            $this->eventMapper = $this->serviceLocator->get('playgroundflow_event_mapper');
        }

        return $this->eventMapper;
    }

    /**
     * setEventMapper
     *
     * @param  EventMapperInterface $eventMapper
     * @return Event
     */
    public function setEventMapper(EventMapperInterface $eventMapper)
    {
        $this->eventMapper = $eventMapper;

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
