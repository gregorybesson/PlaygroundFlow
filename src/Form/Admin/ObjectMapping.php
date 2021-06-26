<?php
namespace PlaygroundFlow\Form\Admin;

use PlaygroundFlow\Options\ModuleOptions;
use Laminas\Form\Form;
use Laminas\Form\Element;
use ZfcUser\Form\ProvidesEventsForm;
use Laminas\Mvc\I18n\Translator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Laminas\ServiceManager\ServiceManager;

class ObjectMapping extends ProvidesEventsForm
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
        $hydrator = new DoctrineHydrator($entityManager, 'PlaygroundFlow\Entity\OpenGraphObjectMapping');
        $hydrator->addStrategy('object', new \PlaygroundCore\Stdlib\Hydrator\Strategy\ObjectStrategy());
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
            'name' => 'object',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => $translator->translate('Object', 'playgroundflow'),
                'object_manager' => $entityManager,
                'target_class' => 'PlaygroundFlow\Entity\OpenGraphObject',
                'property' => 'label'
            ),
            'attributes' => array(
                'required' => true,
                //'multiple' => 'multiple',
            )
        ));

        $this->add(array(
            'name' => 'xpath',
            'options' => array(
                'label' => $translator->translate('Xpath', 'playgroundflow')
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Xpath', 'playgroundflow')
            )
        ));

        $objectAttributeMappingFieldset = new ObjectAttributeMappingFieldset(null, $sm, $translator);
        $this->add(array(
            'type'    => 'Laminas\Form\Element\Collection',
            'name'    => 'attributes',
            'options' => array(
                'id'    => 'attributes',
                'label' => $translator->translate('Attributes', 'playgroundflow'),
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'allow_remove' => true,
                'target_element' => $objectAttributeMappingFieldset
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
