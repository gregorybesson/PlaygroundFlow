<?php

namespace PlaygroundFlow\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventManagerAwareTrait;
use PlaygroundFlow\Options\ModuleOptions;
use PlaygroundCore\Filter\Sanitize;
use Zend\Stdlib\ErrorHandler;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager;

class Domain
{
    use EventManagerAwareTrait;

    /**
     * @var DomainMapperInterface
     */
    protected $domainMapper;
    
    /**
     * @var StoryMappingMapperInterface
     */
    protected $storyMappingMapper;
    
    /**
     * @var ObjectAttributeMapperInterface
     */
    protected $objectAttributeMappingMapper;

    /**
     * @var ObjectMapperInterface
     */
    protected $objectMappingMapper;

    /**
     * @var DomainServiceOptionsInterface
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
        $domain  = new \PlaygroundFlow\Entity\OpenGraphDomain();
        $form  = $this->serviceLocator->get('playgroundflow_domain_form');
        $form->bind($domain);
        $form->setData($data);
        
        if (!$form->isValid()) {
            return false;
        }
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('domain' => $domain, 'data' => $data));
        $this->getDomainMapper()->insert($domain);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('domain' => $domain, 'data' => $data));
        
        return $domain;
    }

    public function edit(array $data, $domain)
    {
        $form  = $this->serviceLocator->get('playgroundflow_domain_form');
        $form->bind($domain);
        $form->setData($data);
         
        if (!$form->isValid()) {
            return false;
        }
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('domain' => $domain, 'data' => $data));
        $this->getDomainMapper()->update($domain);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('domain' => $domain, 'data' => $data));

        return $domain;
    }
    
    public function createStory(array $data)
    {
        $mapping  = new \PlaygroundFlow\Entity\OpenGraphStoryMapping();
        $form  = $this->serviceLocator->get('playgroundflow_storymapping_form');
        $form->bind($mapping);
        $form->setData($data);
        
        $path = $this->getOptions()->getMediaPath() . DIRECTORY_SEPARATOR;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $media_url = $this->getOptions()->getMediaUrl() . '/';
         
        $domain = $this->getDomainMapper()->findById($data['domainId']);
    
        if (!$form->isValid()) {
            return false;
        }
         
        $mapping->setDomain($domain);
         
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('mapping' => $mapping, 'data' => $data));
        $mapping = $this->getStoryMappingMapper()->insert($mapping);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('mapping' => $mapping, 'data' => $data));
        
        if (!empty($data['uploadPicto']['tmp_name'])) {
            ErrorHandler::start();
            $data['uploadPicto']['name'] = $this->fileNewname($path, $mapping->getId() . "-" . $data['uploadPicto']['name']);
            move_uploaded_file($data['uploadPicto']['tmp_name'], $path . $data['uploadPicto']['name']);
            $mapping->setPicto($media_url . $data['uploadPicto']['name']);
            ErrorHandler::stop(true);
        }
        $mapping = $this->getStoryMappingMapper()->update($mapping);
    
        /*
        $objectAttributes = $mapping->getStory()->getObject()->getAttributes();
        $existingMapping = $mapping->getAttributes();
        $existingMappingArray = array();
        if($existingMappingArray){
            foreach($existingMapping as $attMap){
                $existingMappingArray[]=$attMap->getAttribute()->getCode();
            }
        }
        foreach($objectAttributes as $attribute){
            
            if(! in_array($attribute->getCode(), $existingMappingArray)){
                $attributeMapping = new \PlaygroundFlow\Entity\OpenGraphObjectAttributeMapping();
                $attributeMapping->setStoryMapping($mapping);
                $attributeMapping->setObject($mapping->getStory()->getObject());
                $attributeMapping->setAttribute($attribute);
                $attributeMapping->setXpath='';
                
                $this->getObjectAttributeMappingMapper()->insert($attributeMapping);
            }
            
        }*/
        return $mapping;
    }
    
    public function editStory(array $data, $mapping)
    {
        $form  = $this->serviceLocator->get('playgroundflow_storymapping_form');
        $form->bind($mapping);
        $form->setData($data);
        
        $path = $this->getOptions()->getMediaPath() . DIRECTORY_SEPARATOR;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $media_url = $this->getOptions()->getMediaUrl() . '/';
         
        $domain = $this->getDomainMapper()->findById($data['domainId']);
    
        if (!$form->isValid()) {
            print_r($form->getMessages());
            die();
            return false;
        }
         
        $mapping->setDomain($domain);
        
        if (!empty($data['uploadPicto']['tmp_name'])) {
            ErrorHandler::start();
            $data['uploadPicto']['name'] = $this->fileNewname($path, $mapping->getId() . "-" . $data['uploadPicto']['name']);
            move_uploaded_file($data['uploadPicto']['tmp_name'], $path . $data['uploadPicto']['name']);
            $mapping->setPicto($media_url . $data['uploadPicto']['name']);
            ErrorHandler::stop(true);
        }
         
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('attribute' => $mapping, 'data' => $data));
        $this->getStoryMappingMapper()->update($mapping);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('attribute' => $mapping, 'data' => $data));
    
        /*$objectAttributes = $mapping->getStory()->getObject()->getAttributes();
    	$existingMapping = $mapping->getAttributes();
    	$existingMappingArray = array();
    	foreach($existingMapping as $attMap){
    		$existingMappingArray[]=$attMap->getAttribute()->getCode();
    	}
    	foreach($objectAttributes as $attribute){
    		
    		if(! in_array($attribute->getCode(), $existingMappingArray)){
    			$attributeMapping = new \PlaygroundFlow\Entity\OpenGraphObjectAttributeMapping();
    			$attributeMapping->setStoryMapping($mapping);
    			$attributeMapping->setObject($mapping->getStory()->getObject());
    			$attributeMapping->setAttribute($attribute);
    			$attributeMapping->setXpath='';
    			
    			$this->getObjectAttributeMappingMapper()->insert($attributeMapping);
    		}
    		
    	}*/
        
        return $mapping;
    }

    public function createObject(array $data, $objectMapping)
    {
        $form  = $this->serviceLocator->get('playgroundflow_objectmapping_form');
        $form->bind($objectMapping);
        $form->setData($data);
    
        if (!$form->isValid()) {
            return false;
        }
    
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('objectMapping' => $objectMapping, 'data' => $data));
        $this->getObjectMappingMapper()->insert($objectMapping);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('objectMapping' => $objectMapping, 'data' => $data));
    
        return $objectMapping;
    }
    
    public function editObject(array $data, $objectMapping)
    {
        $form  = $this->serviceLocator->get('playgroundflow_objectmapping_form');
        
        // When the last attribute is removed, we have to do this trick...
        // https://github.com/zendframework/zf2/issues/2761
        if (!isset($data['attributes'])) {
            $form->remove('attributes');
            $objectMapping->clearAttributes();
            $objectMapping = $this->getObjectMappingMapper()->update($objectMapping);
        }
        
        $form->bind($objectMapping);
        $form->setData($data);
    
        if (!$form->isValid()) {
            return false;
        }
    
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('objectMapping' => $objectMapping, 'data' => $data));
        $this->getObjectMappingMapper()->update($objectMapping);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('objectMapping' => $objectMapping, 'data' => $data));
    
        return $objectMapping;
    }
    
    public function editAttribute(array $data, $attributeMapping)
    {
        $form  = $this->serviceLocator->get('playgroundflow_objectattributemapping_form');
        $form->bind($attributeMapping);
        $form->setData($data);
    
        if (!$form->isValid()) {
            return false;
        }
    
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('attributeMapping' => $attributeMapping, 'data' => $data));
        $this->getObjectAttributeMappingMapper()->update($attributeMapping);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('attributeMapping' => $attributeMapping, 'data' => $data));
         
        return $attributeMapping;
    }

    /**
    * getDomain : get domain
    * @param Controller $controller
    *
    * return Domain $domain
    */
    public function getDomain($controller)
    {
        $origin = parse_url($controller->getRequest()->getHeader('Referer'));
        $uri = $controller->getRequest()->getUri();
        $domainId = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());

        if (isset($origin['query'])) {
            parse_str($origin['query'], $query);
            $domainId = $query['xdm_e'];
        }

        $domain = $this->getDomainMapper()->findOneBy(array(
            'domain' => $domainId
        ));

        return $domain;
    }

    /**
     * getDomainMapper
     *
     * @return DomainMapperInterface
     */
    public function getDomainMapper()
    {
        if (null === $this->domainMapper) {
            $this->domainMapper = $this->serviceLocator->get('playgroundflow_domain_mapper');
        }

        return $this->domainMapper;
    }

    /**
     * setDomainMapper
     *
     * @param  DomainMapperInterface $domainMapper
     * @return Domain
     */
    public function setDomainMapper(DomainMapperInterface $domainMapper)
    {
        $this->domainMapper = $domainMapper;

        return $this;
    }
    
    /**
     * getStoryMappingMapper
     *
     * @return StoryMappingMapperInterface
     */
    public function getStoryMappingMapper()
    {
        if (null === $this->storyMappingMapper) {
            $this->storyMappingMapper = $this->serviceLocator->get('playgroundflow_storyMapping_mapper');
        }
    
        return $this->storyMappingMapper;
    }
    
    /**
     * setStoryMappingMapper
     *
     * @param  StoryMappingMapperInterface $storyMappingMapper
     * @return StoryMapping
     */
    public function setStoryMappingMapper(StoryMappingMapperInterface $storyMappingMapper)
    {
        $this->storyMappingMapper = $storyMappingMapper;
    
        return $this;
    }
    
    /**
     * getObjectAttributeMappingMapper
     *
     * @return ObjectAttributeMapperInterface
     */
    public function getObjectAttributeMappingMapper()
    {
        if (null === $this->objectAttributeMappingMapper) {
            $this->objectAttributeMappingMapper = $this->serviceLocator->get('playgroundflow_objectattributemapping_mapper');
        }
    
        return $this->objectAttributeMappingMapper;
    }
    
    /**
     * setObjectAttributeMapper
     *
     * @param  ObjectAttributeMapperInterface $objectAttributeMapper
     * @return ObjectAttribute
     */
    public function setObjectAttributeMappingMapper(ObjectAttributeMappingMapperInterface $objectAttributeMappingMapper)
    {
        $this->objectAttributeMappingMapper = $objectAttributeMappingMapper;
    
        return $this;
    }
    
    /**
     * getObjectMappingMapper
     *
     * @return ObjectMapperInterface
     */
    public function getObjectMappingMapper()
    {
        if (null === $this->objectMappingMapper) {
            $this->objectMappingMapper = $this->serviceLocator->get('playgroundflow_objectmapping_mapper');
        }
    
        return $this->objectMappingMapper;
    }
    
    /**
     * setObjectMapper
     *
     * @param  ObjectMapperInterface $objectMapper
     * @return Object
     */
    public function setObjectMappingMapper(ObjectMappingMapperInterface $objectMappingMapper)
    {
        $this->objectMappingMapper = $objectMappingMapper;
    
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
    
    public function fileNewname($path, $filename, $generate = false)
    {
        $sanitize = new Sanitize();
        $name = $sanitize->filter($filename);
        $newpath = $path.$name;
    
        if ($generate) {
            if (file_exists($newpath)) {
                $filename = pathinfo($name, PATHINFO_FILENAME);
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                 
                $name = $filename .'_'. rand(0, 99) .'.'. $ext;
            }
        }
    
        unset($sanitize);
    
        return $name;
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
