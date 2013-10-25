<?php
namespace PlaygroundFlow\Form\Admin;

use PlaygroundFlow\Options\ModuleOptions;
use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\I18n\Translator\Translator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\ServiceManager\ServiceManager;

class ObjectAttribute extends ProvidesEventsForm
{

    /**
     *
     * @var ModuleOptions
     */
    protected $module_options;

    protected $serviceManager;

    public function __construct ($name = null, ServiceManager $sm, Translator $translator)
    {
        parent::__construct($name);

        $this->setServiceManager($sm);

        $entityManager = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
        
        // Mapping of an Entity to get value by getId()... Should be taken in charge by Doctrine Hydrator Strategy...
        // having to fix a DoctrineModule bug :( https://github.com/doctrine/DoctrineModule/issues/180
        // so i've extended DoctrineHydrator ...
        $hydrator = new DoctrineHydrator($entityManager, 'PlaygroundFlow\Entity\OpenGraphObjectAttribute');
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
        	'name' => 'objectId',
        	'type'  => 'Zend\Form\Element\Hidden',
       		'attributes' => array(
       			'value' => 0,
       		),
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
        
        $this->add(array(
        	'type' => 'Zend\Form\Element\Select',
        	'name' => 'type',
        	'attributes' =>  array(
       			'id' => 'type',
      			'options' => array(
       				'boolean' => $translator->translate('Boolean', 'playgroundflow'),
    				'float' => $translator->translate('Float', 'playgroundflow'),
        			'integer' => $translator->translate('Integer', 'playgroundflow'),
    				'string' => $translator->translate('String', 'playgroundflow'),
        			'array' => $translator->translate('Array', 'playgroundflow'),
      			    'datetime' => $translator->translate('DateTime', 'playgroundflow'),
      			    'date' => $translator->translate('Date', 'playgroundflow'),
   				),
       		),
       		'options' => array(
      			'empty_option' => $translator->translate('Type de l\'attribut', 'playgroundflow'),
       			'label' => $translator->translate('Type de l\'attribut', 'playgroundflow'),
       		),
        ));

        $this->add(array(
        	'type' => 'Zend\Form\Element\Select',
        	'name' => 'mandatory',
       		'options' => array(
       			'value_options' => array(
       				'0' => $translator->translate('No', 'playgroundflow'),
       				'1' => $translator->translate('Yes', 'playgroundflow'),
       			),
   				'label' => $translator->translate('Is this attribute mandatory ?', 'playgroundflow'),
       		),
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
    public function getServiceManager ()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param  ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager (ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
