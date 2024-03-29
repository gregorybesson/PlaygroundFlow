<?php

namespace PlaygroundFlow\Form\Admin;

use PlaygroundFlow\Entity\OpenGraphObjectAttributeMapping;
use Laminas\Form\Fieldset;
use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\Mvc\I18n\Translator;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Laminas\ServiceManager\ServiceManager;
use Laminas\InputFilter\InputFilterProviderInterface;

class ObjectAttributeMappingFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name = null, ServiceManager $serviceManager, Translator $translator)
    {
        parent::__construct($name);

        $this->setServiceManager($serviceManager);

        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        $this->setHydrator(
            new DoctrineHydrator(
                $entityManager,
                'PlaygroundFlow\Entity\OpenGraphObjectAttributeMapping'
            )
        )
            ->setObject(new OpenGraphObjectAttributeMapping());

        $this->add(
            array(
                'type' => 'Laminas\Form\Element\Hidden',
                'name' => 'id'
            )
        );

        $this->add(
            array(
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
            )
        );

        $this->add(
            array(
                'name' => 'attributeArray',
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'options' => array(
                    'empty_option' => $translator->translate('Select an attribute', 'playgroundflow'),
                    'label' => $translator->translate('Attribute Array', 'playgroundflow'),
                    'object_manager' => $entityManager,
                    'target_class' => 'PlaygroundFlow\Entity\OpenGraphObjectAttribute',
                    'property' => 'label'
                ),
                'attributes' => array(
                    'required' => false,
                    //'multiple' => 'multiple',
                )
            )
        );

        $this->add(
            array(
                'name' => 'xpath',
                'options' => array(
                    'label' => $translator->translate('Xpath', 'playgroundflow')
                ),
                'attributes' => array(
                    'type' => 'text',
                    'placeholder' => $translator->translate('Xpath', 'playgroundflow')
                )
            )
        );

        $this->add(
            array(
                'type' => 'Laminas\Form\Element\Select',
                'name' => 'comparison',
                'options' => array(
                        'empty_option' => $translator->translate('Comparison ?', 'playgroundflow'),
                        'value_options' => array(
                            'less_than'  => $translator->translate('Less than', 'playgroundflow'),
                            'equals' => $translator->translate('Equals', 'playgroundflow'),
                            'more_than' => $translator->translate('More than', 'playgroundflow'),
                            'not_empty' => $translator->translate('Not empty', 'playgroundflow'),
                            'empty' => $translator->translate('Empty', 'playgroundflow'),
                            'contains' => $translator->translate('Contains', 'playgroundflow'),
                            'does_not_contain' => $translator->translate('Does not contain', 'playgroundflow'),
                        ),
                        'label' => $translator->translate('Comparison', 'playgroundflow'),
                ),
                'attributes' => array(
                    'required' => false,
                )
            )
        );

        $this->add(
            array(
                'name' => 'value',
                'type' => 'Laminas\Form\Element\Text',
                'attributes' => array(
                    'placeholder' => $translator->translate('Value', 'playgroundflow'),
                ),
                'options' => array(
                    'label' => $translator->translate('Value', 'playgroundflow'),
                ),
            )
        );

        $this->add(
            [
                'name' => 'overloadPoints',
                'type' => 'Laminas\Form\Element\Checkbox',
                'options' => array(
                    'label' => $translator->translate('Overload the points from the story added to the leaderboard', 'playgroundflow'),
                ),
            ]
        );

        $this->add(
            array(
                'type' => 'Laminas\Form\Element\Button',
                'name' => 'remove',
                'options' => array(
                    'label' => $translator->translate('Delete', 'playgroundflow'),
                ),
                'attributes' => array(
                    'class' => 'delete-button',
                )
            )
        );
    }

    public function getInputFilterSpecification()
    {
        return array(
            'comparison' => array(
                'required' => false,
                'allowEmpty' => true,
            ),
            'attributeArray' => array(
                'required' => false,
                'allowEmpty' => true,
            ),
        );
    }

    /**
     *
     * @return array
     */
    public function getAttributes(): array
    {
        $attributesArray = array();
        $objectService = $this->getServiceManager()->get('playgroundflow_object_service');
        $attributes = $objectService->getObjectAttributeMapper()->findAll();

        foreach ($attributes as $attribute) {
            $attributesArray["_".$attribute->getId()] = $attribute->getLabel();
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
