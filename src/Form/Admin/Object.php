<?php
namespace PlaygroundFlow\Form\Admin;

use PlaygroundFlow\Options\ModuleOptions;
use Laminas\Form\Form;
use Laminas\Form\Element;
use LmcUser\Form\ProvidesEventsForm;
use Laminas\Mvc\I18n\Translator;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Laminas\ServiceManager\ServiceManager;

class Object extends ProvidesEventsForm
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
        $hydrator = new DoctrineHydrator($entityManager, 'PlaygroundFlow\Entity\OpenGraphObject');
        $hydrator->addStrategy('parent', new \PlaygroundCore\Stdlib\Hydrator\Strategy\ObjectStrategy());
        $this->setHydrator($hydrator);

        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add(array(
            'name' => 'id',
            'type' => 'Laminas\Form\Element\Hidden',
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
                'placeholder' => $translator->translate('Code', 'playgroundflow')
            )
        ));

        $this->add(array(
            'type' => 'Laminas\Form\Element\Textarea',
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
        
        $objects = $this->getObjects();
        $this->add(array(
            'type' => 'Laminas\Form\Element\Select',
            'name' => 'parent',
            'options' => array(
                'empty_option' => $translator->translate('This object is the root', 'playgroundflow'),
                'value_options' => $objects,
                'label' => $translator->translate('Inherits', 'playgroundflow')
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
}
