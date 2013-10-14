<?php

namespace PlaygroundFlow;

use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
{
    protected $eventsArray = array();
    
    public function onBootstrap(MvcEvent $e)
    {
        $application     = $e->getTarget();
        $serviceManager  = $application->getServiceManager();
        $eventManager    = $application->getEventManager();

        $options = $serviceManager->get('playgroundcore_module_options');
        $locale = $options->getLocale();
        $translator = $serviceManager->get('translator');
        if (!empty($locale)) {
            //translator
            $translator->setLocale($locale);

            // plugins
            $translate = $serviceManager->get('viewhelpermanager')->get('translate');
            $translate->getTranslator()->setLocale($locale);
        }
        AbstractValidator::setDefaultTranslator($translator,'playgroundcore');
        
        $eventManager->attach($serviceManager->get('playgroundflow_storytelling_listener'));
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                    'playgroundflow_doctrine_em' => 'doctrine.entitymanager.orm_default',
            ),

            'invokables' => array(
                    'playgroundflow_flow_service'         => 'PlaygroundFlow\Service\Flow',
            		'playgroundflow_action_service'       => 'PlaygroundFlow\Service\Action',
            		'playgroundflow_object_service'       => 'PlaygroundFlow\Service\Object',
            		'playgroundflow_story_service'        => 'PlaygroundFlow\Service\Story',
            		'playgroundflow_domain_service'       => 'PlaygroundFlow\Service\Domain',
                    'playgroundflow_broadcast_service'    => 'PlaygroundFlow\Service\Broadcast',
                    'playgroundflow_storytelling_service' => 'PlaygroundFlow\Service\StoryTelling',
                    'playgroundflow_storytelling_listener'=> 'PlaygroundFlow\Service\StoryTellingListener'
            ),

            'factories' => array(
                'playgroundflow_module_options' => function ($sm) {
                    $config = $sm->get('Configuration');

                    return new Options\ModuleOptions(isset($config['playgroundflow']) ? $config['playgroundflow'] : array());
                },
                'playgroundflow_action_mapper' => function ($sm) {
                	$mapper = new \PlaygroundFlow\Mapper\Action(
                			$sm->get('doctrine.entitymanager.orm_default'),
                			$sm->get('playgroundflow_module_options')
                	);
                
                	return $mapper;
                },
                'playgroundflow_action_form' => function($sm) {
                	$translator = $sm->get('translator');
                	$form = new Form\Admin\Action(null, $sm, $translator);
                	$action = new Entity\OpenGraphAction();
                	$form->setInputFilter($action->getInputFilter());
                
                	return $form;
                },
                'playgroundflow_object_mapper' => function ($sm) {
                	$mapper = new \PlaygroundFlow\Mapper\Object(
                			$sm->get('doctrine.entitymanager.orm_default'),
                			$sm->get('playgroundflow_module_options')
                	);
                
                	return $mapper;
                },
                'playgroundflow_objectattribute_mapper' => function ($sm) {
                	$mapper = new \PlaygroundFlow\Mapper\ObjectAttribute(
                			$sm->get('doctrine.entitymanager.orm_default'),
                			$sm->get('playgroundflow_module_options')
                	);
                
                	return $mapper;
                },
                'playgroundflow_story_mapper' => function ($sm) {
                	$mapper = new \PlaygroundFlow\Mapper\Story(
                			$sm->get('doctrine.entitymanager.orm_default'),
                			$sm->get('playgroundflow_module_options')
                	);
                
                	return $mapper;
                },
                'playgroundflow_domain_mapper' => function ($sm) {
                	$mapper = new \PlaygroundFlow\Mapper\Domain(
                			$sm->get('doctrine.entitymanager.orm_default'),
                			$sm->get('playgroundflow_module_options')
                	);
                
                	return $mapper;
                },
                'playgroundflow_storymapping_mapper' => function ($sm) {
                	$mapper = new \PlaygroundFlow\Mapper\StoryMapping(
                			$sm->get('doctrine.entitymanager.orm_default'),
                			$sm->get('playgroundflow_module_options')
                	);
                
                	return $mapper;
                },
                'playgroundflow_objectattributemapping_mapper' => function ($sm) {
                	$mapper = new \PlaygroundFlow\Mapper\ObjectAttributeMapping(
                			$sm->get('doctrine.entitymanager.orm_default'),
                			$sm->get('playgroundflow_module_options')
                	);
                
                	return $mapper;
                },
                'playgroundflow_objectmapping_mapper' => function ($sm) {
                    $mapper = new \PlaygroundFlow\Mapper\ObjectMapping(
                        $sm->get('doctrine.entitymanager.orm_default'),
                        $sm->get('playgroundflow_module_options')
                    );
                
                    return $mapper;
                },
                'playgroundflow_storytelling_mapper' => function ($sm) {
                    $mapper = new \PlaygroundFlow\Mapper\StoryTelling(
                        $sm->get('doctrine.entitymanager.orm_default'),
                        $sm->get('playgroundflow_module_options')
                    );
                
                    return $mapper;
                },
                'playgroundflow_object_form' => function($sm) {
                	$translator = $sm->get('translator');
                	$form = new Form\Admin\Object(null, $sm, $translator);
                	$object = new Entity\OpenGraphObject();
                	$form->setInputFilter($object->getInputFilter());
                
                	return $form;
                },
                'playgroundflow_objectattribute_form' => function($sm) {
                	$translator = $sm->get('translator');
                	$form = new Form\Admin\ObjectAttribute(null, $sm, $translator);
                	$objectAttribute = new Entity\OpenGraphObjectAttribute();
                	$form->setInputFilter($objectAttribute->getInputFilter());
                
                	return $form;
                },
                'playgroundflow_story_form' => function($sm) {
                	$translator = $sm->get('translator');
                	$form = new Form\Admin\Story(null, $sm, $translator);
                	$story = new Entity\OpenGraphStory();
                	$form->setInputFilter($story->getInputFilter());
                
                	return $form;
                },
                'playgroundflow_domain_form' => function($sm) {
                	$translator = $sm->get('translator');
                	$form = new Form\Admin\Domain(null, $sm, $translator);
                	$domain = new Entity\OpenGraphDomain();
                	$form->setInputFilter($domain->getInputFilter());
                
                	return $form;
                },
                'playgroundflow_storymapping_form' => function($sm) {
                	$translator = $sm->get('translator');
                	$form = new Form\Admin\StoryMapping(null, $sm, $translator);
                	$story = new Entity\OpenGraphStoryMapping();
                	$form->setInputFilter($story->getInputFilter());
                
                	return $form;
                },
                'playgroundflow_objectattributemapping_form' => function($sm) {
                	$translator = $sm->get('translator');
                	$form = new Form\Admin\ObjectAttributeMapping(null, $sm, $translator);
                	$attribute = new Entity\OpenGraphObjectAttributeMapping();
                	$form->setInputFilter($attribute->getInputFilter());
                
                	return $form;
                },
                'playgroundflow_objectmapping_form' => function($sm) {
                    $translator = $sm->get('translator');
                    $form = new Form\Admin\ObjectMapping(null, $sm, $translator);
                    $object = new Entity\OpenGraphObjectMapping();
                    $form->setInputFilter($object->getInputFilter());
                
                    return $form;
                },
            ),
        );
    }
}
