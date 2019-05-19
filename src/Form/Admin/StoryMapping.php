<?php
namespace PlaygroundFlow\Form\Admin;

use PlaygroundFlow\Options\ModuleOptions;
use Zend\Form\Form;
use Zend\Form\Element;
use ZfcUser\Form\ProvidesEventsForm;
use Zend\Mvc\I18n\Translator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\ServiceManager\ServiceManager;

class StoryMapping extends ProvidesEventsForm
{

    /**
     *
     * @var ModuleOptions
     */
    protected $module_options;

    protected $serviceManager;

    public function __construct($name = null, ServiceManager $sm, Translator $translator)
    {
        parent::__construct($name);

        $this->setServiceManager($sm);

        $entityManager = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
        
        // Mapping of an Entity to get value by getId()... Should be taken in charge by Doctrine Hydrator Strategy...
        // having to fix a DoctrineModule bug :( https://github.com/doctrine/DoctrineModule/issues/180
        // so i've extended DoctrineHydrator ...
        $hydrator = new DoctrineHydrator($entityManager, 'PlaygroundFlow\Entity\OpenGraphStoryMapping');
        $hydrator->addStrategy('story', new \PlaygroundCore\Stdlib\Hydrator\Strategy\ObjectStrategy());
        $this->setHydrator($hydrator);

        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => 0
            )
        ));

        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => $translator->translate('Title', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Title', 'playgroundflow')
            )
        ));
        
        $this->add(array(
            'name' => 'domainId',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => 0,
            ),
        ));

        $this->add(array(
          'name' => 'webTechnoId',
          'type'  => 'Zend\Form\Element\Hidden',
          'attributes' => array(
            'value' => 0,
          ),
        ));
        
        $stories = $this->getStories();
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'story',
            'options' => array(
                'value_options' => $stories,
                'label' => $translator->translate('Story', 'playgroundflow')
            )
        ));

        $leaderboardTypes = $this->getLeaderboardTypes();
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'leaderboardType',
            'options' => array(
                'value_options' => $leaderboardTypes,
                'label' => $translator->translate('leaderboardType', 'playgroundflow')
            )
        ));
        
        $widgets = $this->getWidgets();
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'widget',
            'options' => array(
                'empty_option' => $translator->translate('Default widget', 'playgroundflow'),
                'value_options' => $widgets,
                'label' => $translator->translate('Widget', 'playgroundflow')
            )
        ));
        
        $this->add(array(
            'name' => 'points',
            'options' => array(
                'label' => $translator->translate('Points', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Points', 'playgroundflow')
            )
        ));
        
        $this->add(array(
            'name' => 'countLimit',
            'options' => array(
                'label' => $translator->translate('Limiter le nombre', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Limite du nombre', 'playgroundflow')
            )
        ));
        
        $this->add(array(
            'name' => 'hint',
            'options' => array(
                'label' => $translator->translate('Hint', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Hint', 'playgroundflow')
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'displayNotification',
            'options' => array(
                'label' => $translator->translate('Display notication to player', 'playgroundflow'),
            ),
            'attributes' => array(
                //'checked' => true
            )
        ));
        
        $this->add(array(
            'name' => 'notification',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => $translator->translate('Notification Message', 'playgroundflow')
            ),
            'attributes' => array(
                'cols' => '10',
                'rows' => '10',
                'id' => 'notification'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'displayActivityStream',
            'options' => array(
                'label' => $translator->translate('Display on activity Stream', 'playgroundflow'),
            ),
            'attributes' => array(
                //'checked' => true
            )
        ));
        
        $this->add(array(
            'name' => 'activityStream',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => $translator->translate('Activity Stream Message', 'playgroundflow')
            ),
            'attributes' => array(
                'cols' => '10',
                'rows' => '10',
                'id' => 'activityStream'
            )
        ));
        
        // Adding an empty upload field to be able to correctly handle this on
        // the service side.
        $this->add(array(
            'name' => 'uploadPicto',
            'attributes' => array(
                'type' => 'file'
            ),
            'options' => array(
                'label' => $translator->translate('Picto', 'playgroundflow')
            )
        ));
        $this->add(array(
            'name' => 'picto',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => ''
            )
        ));
        
        $this->add(array(
            'name' => 'eventBeforeUrl',
            'options' => array(
                    'label' => $translator->translate('Event before Url', 'playgroundflow')
            ),
            'attributes' => array(
                    'type' => 'text',
                    'placeholder' => $translator->translate('Event before Url', 'playgroundflow')
            )
        ));
        
        $this->add(array(
            'name' => 'eventBeforeXpath',
            'options' => array(
                'label' => $translator->translate('Event before Xpath', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Event before Xpath', 'playgroundflow')
            )
        ));
        
        $this->add(array(
            'name' => 'eventAfterUrl',
            'options' => array(
                'label' => $translator->translate('Event after Url', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Event after Url', 'playgroundflow')
            )
        ));
        
        $this->add(array(
            'name' => 'eventAfterXpath',
            'options' => array(
                'label' => $translator->translate('Event after Xpath', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Event after Xpath', 'playgroundflow')
            )
        ));
        
        $this->add(array(
            'name' => 'conditionsUrl',
            'options' => array(
                    'label' => $translator->translate('Conditions URL', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Conditions URL', 'playgroundflow')
            )
        ));
        
        $this->add(array(
            'name' => 'conditionsXpath',
            'options' => array(
                'label' => $translator->translate('Conditions Xpath', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Conditions Xpath', 'playgroundflow')
            )
        ));

        $submitElement = new Element\Button('submit');
        $submitElement->setLabel($translator->translate('Create', 'playgroundflow'))
            ->setAttributes(array(
            'type' => 'submit'
            ));

        $this->add($submitElement, array(
            'priority' => - 100
        ));
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
     * @param  ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
    
    /**
     *
     * @return array
     */
    public function getStories()
    {
        $storiesArray = array();
        $storyService = $this->getServiceManager()->get('playgroundflow_story_service');
        $stories = $storyService->getStoryMapper()->findAll();
    
        foreach ($stories as $story) {
            $storiesArray[$story->getId()] = $story->getLabel();
        }
    
        return $storiesArray;
    }

    /**
     * retrieve all leaderboard type for associate to storyMapping
     *
     * @return array $leaderboardTypesArray
     */
    public function getLeaderboardTypes()
    {
        $leaderboardTypesArray = array();
        $leaderboardTypesService = $this->getServiceManager()->get('playgroundreward_leaderboardtype_service');
        $leaderboardTypes = $leaderboardTypesService->getLeaderboardTypeMapper()->findAll();
    
        foreach ($leaderboardTypes as $leaderboardType) {
            $leaderboardTypesArray[$leaderboardType->getId()] = $leaderboardType->getName();
        }

        return $leaderboardTypesArray;
    }
    
    /**
     *
     * @return array
     */
    public function getWidgets()
    {
        $widgetsArray = array();
        $widgetService = $this->getServiceManager()->get('playgroundflow_widget_service');
        $widgets = $widgetService->getWidgetMapper()->findAll();
    
        foreach ($widgets as $widget) {
            $widgetsArray[$widget->getId()] = $widget->getTitle();
        }
    
        return $widgetsArray;
    }
}
