<?php
namespace PlaygroundFlow\Form\Admin;

use PlaygroundFlow\Options\ModuleOptions;
use Zend\Form\Form;
use Zend\Form\Element;
use ZfcUser\Form\ProvidesEventsForm;
use Zend\Mvc\I18n\Translator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\ServiceManager\ServiceManager;

class Story extends ProvidesEventsForm
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
        
        $hydrator = new DoctrineHydrator($entityManager, 'PlaygroundFlow\Entity\OpenGraphStory');
        //$hydrator->addStrategy('object', new \PlaygroundCore\Stdlib\Hydrator\Strategy\ObjectStrategy());
        $hydrator->addStrategy('action', new \PlaygroundCore\Stdlib\Hydrator\Strategy\ObjectStrategy());
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
            'name' => 'code',
            'options' => array(
                'label' => $translator->translate('Code', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Code', 'playgroundflow')
            )
        ));

        $this->add(array(
            'name' => 'label',
            'options' => array(
                'label' => $translator->translate('Label', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Label', 'playgroundflow')
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'definition',
            'options' => array(
                'label' => $translator->translate('Definition', 'playgroundflow')
            ),
            'attributes' => array(
                'cols' => '10',
                'rows' => '10',
                'id' => 'definition'
            )
        ));
        
        $actions = $this->getActions();
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'action',
            'options' => array(
                'value_options' => $actions,
                'label' => $translator->translate('Action', 'playgroundflow')
            )
        ));
        
        $this->add(array(
         'name' => 'objects',
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'options' => array(
                'empty_option' => $translator->translate('Select an object', 'playgroundflow'),
                'label' => $translator->translate('Objects', 'playgroundflow'),
                'object_manager' => $entityManager,
                'target_class' => 'PlaygroundFlow\Entity\OpenGraphObject',
                'property' => 'label'
            ),
            'attributes' => array(
                'required' => false,
                //'multiple' => 'multiple',
            )
        ));
        
/*        $storyObjectFieldset = new StoryObjectFieldset(null,$sm,$translator);
        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'objects',
            'options' => array(
                'id'    => 'objects',
                'label' => $translator->translate('Objects', 'playgroundreward'),
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'allow_remove' => true,
                'target_element' => $storyObjectFieldset
            )
        ));
*/
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
    public function getObjects()
    {
        $objectsArray = array();
        $objectService = $this->getServiceManager()->get('playgroundflow_object_service');
        $objects = $objectService->getObjectMapper()->findAll();
    
        foreach ($objects as $object) {
            $objectsArray[$object->getId()] = $object->getLabel();
        }
    
        return $objectsArray;
    }
    
    /**
     *
     * @return array
     */
    public function getActions()
    {
        $actionsArray = array();
        $actionService = $this->getServiceManager()->get('playgroundflow_action_service');
        $actions = $actionService->getActionMapper()->findAll();
    
        foreach ($actions as $action) {
            $actionsArray[$action->getId()] = $action->getLabel();
        }
    
        return $actionsArray;
    }
}
