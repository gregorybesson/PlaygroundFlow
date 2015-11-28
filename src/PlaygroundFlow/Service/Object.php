<?php

namespace PlaygroundFlow\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundFlow\Options\ModuleOptions;

class Object extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var ObjectMapperInterface
     */
    protected $objectMapper;
    
    /**
     * @var ObjectAttributeMapperInterface
     */
    protected $objectAttributeMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var ObjectServiceOptionsInterface
     */
    protected $options;

    public function create(array $data)
    {
        $object  = new \PlaygroundFlow\Entity\OpenGraphObject();
        $form  = $this->getServiceManager()->get('playgroundflow_object_form');
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
        $form  = $this->getServiceManager()->get('playgroundflow_object_form');
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
        $form  = $this->getServiceManager()->get('playgroundflow_objectattribute_form');
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
        $form  = $this->getServiceManager()->get('playgroundflow_objectattribute_form');
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
            $this->objectMapper = $this->getServiceManager()->get('playgroundflow_object_mapper');
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
            $this->objectAttributeMapper = $this->getServiceManager()->get('playgroundflow_objectattribute_mapper');
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
            $this->setOptions($this->getServiceManager()->get('playgroundflow_module_options'));
        }

        return $this->options;
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
     * @param  ServiceManager $locator
     * @return Object
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
