<?php

namespace PlaygroundFlow\Form\Admin;

use PlaygroundFlow\Entity\OpenGraphObjectAttributeMapping;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Mvc\I18n\Translator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\ServiceManager\ServiceManager;

class ObjectAttributeMappingFieldset extends Fieldset
{
    public function __construct($name = null, ServiceManager $serviceManager, Translator $translator)
    {
        parent::__construct($name);
        
        $this->setServiceManager($serviceManager);
        
        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        $this->setHydrator(new DoctrineHydrator($entityManager, 'PlaygroundFlow\Entity\OpenGraphObjectAttributeMapping'))
        ->setObject(new OpenGraphObjectAttributeMapping());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(array(
            'name' => 'attribute',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'empty_option' => $translator->translate('Select an attribute', 'playgroundflow'),
                'label' => $translator->translate('Attribute', 'playgroundflow'),
                'object_manager' => $entityManager,
                'target_class' => 'PlaygroundFlow\Entity\OpenGraphObjectAttribute',
                'property' => 'label'
            ),
            'attributes' => array(
                'required' => false,
                //'multiple' => 'multiple',
            )
        ));
        
        $this->add(array(
            'name' => 'xpath',
            'options' => array(
                'label' => $translator->translate('Xpath', 'playgroundflow')
            ),
            'attributes' => array(
                'required' => false,
                'type' => 'text',
                'placeholder' => $translator->translate('Xpath', 'playgroundflow')
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'comparison',
            'options' => array(
                    'empty_option' => $translator->translate('Comparison ?', 'playgroundflow'),
                    'value_options' => array(
                        'less_than'  => $translator->translate('Less than', 'playgroundflow'),
                        'equals' => $translator->translate('Equals', 'playgroundflow'),
                        'more_than' => $translator->translate('More than', 'playgroundflow'),
                    ),
                    'label' => $translator->translate('Comparison', 'playgroundflow'),
            ),
        ));

        $this->add(array(
            'name' => 'value',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'placeholder' => $translator->translate('Value', 'playgroundflow'),
            ),
            'options' => array(
                'label' => $translator->translate('Value', 'playgroundflow'),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Button',
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
    public function getAttributes()
    {
        $attributesArray = array();
        $objectService = $this->getServiceManager()->get('playgroundflow_object_service');
        $attributes = $objectService->getObjectAttributeMapper()->findAll();
    
        foreach ($attributes as $attribute) {
            $attributesArray[$attribute->getId()] = $attribute->getLabel();
        }
    
        return $attributesArray;
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
