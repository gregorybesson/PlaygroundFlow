<?php

namespace PlaygroundFlow\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventManagerAwareTrait;
use PlaygroundFlow\Options\ModuleOptions;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager;

class Object
{
    use EventManagerAwareTrait;

    /**
     * @var ObjectMapperInterface
     */
    protected $objectMapper;
    
    /**
     * @var ObjectAttributeMapperInterface
     */
    protected $objectAttributeMapper;

    /**
     * @var ObjectServiceOptionsInterface
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

    public function create(array $data)
    {
        $object  = new \PlaygroundFlow\Entity\OpenGraphObject();
        $form  = $this->serviceLocator->get('playgroundflow_object_form');
        $form->bind($object);
        $form->setData($data);
        
        if (!$form->isValid()) {
            return false;
        }
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('object' => $object, 'data' => $data));
        $this->getObjectMapper()->insert($object);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('object' => $object, 'data' => $data));
        
        return $object;
    }

    public function edit(array $data, $object)
    {
        $form  = $this->serviceLocator->get('playgroundflow_object_form');
        $form->bind($object);
        $form->setData($data);
         
        if (!$form->isValid()) {
            return false;
        }
        
        $this->getObjectMapper()->update($object);
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('object' => $object, 'data' => $data));
        $this->getObjectMapper()->insert($object);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('object' => $object, 'data' => $data));

        return $object;
    }

    public function createAttribute(array $data)
    {
        $attribute  = new \PlaygroundFlow\Entity\OpenGraphObjectAttribute();
        $form  = $this->serviceLocator->get('playgroundflow_objectattribute_form');
        $form->bind($attribute);
        $form->setData($data);
        
        $object = $this->getObjectMapper()->findById($data['objectId']);
        
        if (!$form->isValid()) {
            return false;
        }
        
        $attribute->setObject($object);
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('attribute' => $attribute, 'data' => $data));
        $this->getObjectAttributeMapper()->insert($attribute);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('attribute' => $attribute, 'data' => $data));
         
        return $attribute;
    }
    
    public function editAttribute(array $data, $attribute)
    {
        $form  = $this->serviceLocator->get('playgroundflow_objectattribute_form');
        $form->bind($attribute);
        $form->setData($data);
        
        if (!$form->isValid()) {
            return false;
        }
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('attribute' => $attribute, 'data' => $data));
        $this->getObjectAttributeMapper()->update($attribute);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('attribute' => $attribute, 'data' => $data));
    
        return $attribute;
    }

    /**
     * getObjectMapper
     *
     * @return ObjectMapperInterface
     */
    public function getObjectMapper()
    {
        if (null === $this->objectMapper) {
            $this->objectMapper = $this->serviceLocator->get('playgroundflow_object_mapper');
        }

        return $this->objectMapper;
    }

    /**
     * setObjectMapper
     *
     * @param  ObjectMapperInterface $objectMapper
     * @return Object
     */
    public function setObjectMapper(ObjectMapperInterface $objectMapper)
    {
        $this->objectMapper = $objectMapper;

        return $this;
    }
    
    /**
     * getObjectAttributeMapper
     *
     * @return ObjectAttributeMapperInterface
     */
    public function getObjectAttributeMapper()
    {
        if (null === $this->objectAttributeMapper) {
            $this->objectAttributeMapper = $this->serviceLocator->get('playgroundflow_objectattribute_mapper');
        }
    
        return $this->objectAttributeMapper;
    }
    
    /**
     * setObjectAttributeMapper
     *
     * @param  ObjectAttributeMapperInterface $objectAttributeMapper
     * @return Object
     */
    public function setObjectAttributeMapper(ObjectAttributeMapperInterface $objectAttributeMapper)
    {
        $this->objectAttributeMapper = $objectAttributeMapper;
    
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
