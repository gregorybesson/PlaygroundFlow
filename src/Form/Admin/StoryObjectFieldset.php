<?php

namespace PlaygroundFlow\Form\Admin;

use PlaygroundFlow\Entity\OpenGraphObject;
use Laminas\Form\Fieldset;
use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\Mvc\I18n\Translator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Laminas\ServiceManager\ServiceManager;

class StoryObjectFieldset extends Fieldset
{
    public function __construct($name = null, ServiceManager $serviceManager, Translator $translator)
    {
        parent::__construct($name);

        $this->setServiceManager($serviceManager);

        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        $this->setHydrator(new DoctrineHydrator($entityManager, 'PlaygroundFlow\Entity\OpenGraphObject'))
        ->setObject(new OpenGraphObject());

        $this->add(array(
            'type' => 'Laminas\Form\Element\Hidden',
            'name' => 'id'
        ));

        $objects = $this->getObjects();
        $this->add(array(
            'type' => 'Laminas\Form\Element\Select',
            'name' => 'object',
            'options' => array(
                'value_options' => $objects,
                'label' => $translator->translate('Object', 'playgroundflow')
            )
        ));

        $this->add(array(
            'type' => 'Laminas\Form\Element\Button',
            'name' => 'remove',
            'options' => array(
                'label' => $translator->translate('Delete', 'playgroundflow'),
            ),
            'attributes' => array(
                'class' => 'delete-button',
            )
        ));
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
}
