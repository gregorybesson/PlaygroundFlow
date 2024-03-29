<?php

namespace PlaygroundFlow;

use Laminas\Mvc\MvcEvent;
use Laminas\Validator\AbstractValidator;

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
        $translator = $serviceManager->get('MvcTranslator');
        if (!empty($locale)) {
            //translator
            $translator->setLocale($locale);

            // plugins
            $translate = $serviceManager->get('ViewHelperManager')->get('translate');
            $translate->getTranslator()->setLocale($locale);
        }
        AbstractValidator::setDefaultTranslator($translator, 'playgroundcore');

        // I don't attach listeners if the request is a console request
        if ((get_class($e->getRequest()) == 'Laminas\Console\Request')) {
            return;
        }

        $strategy = $serviceManager->get('playgroundflow_storytelling_listener');
        $strategy->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return [
            'factories' => [
                \PlaygroundFlow\View\Helper\StoryTellingWidget::class =>  \PlaygroundFlow\View\Helper\StoryTellingWidgetFactory::class,
            ],
            'aliases' => [
                'storytellingWidget' => \PlaygroundFlow\View\Helper\StoryTellingWidget::class,
            ]
        ];
    }

    public function getServiceConfig()
    {
        return array(
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
                'playgroundflow_action_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\Action(null, $sm, $translator);
                    $action = new Entity\OpenGraphAction();
                    $form->setInputFilter($action->getInputFilter());

                    return $form;
                },
                'playgroundflow_object_mapper' => function ($sm) {
                    $mapper = new \PlaygroundFlow\Mapper\OpenGraphObject(
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
                'playgroundflow_widget_mapper' => function ($sm) {
                    $mapper = new \PlaygroundFlow\Mapper\Widget(
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
                'playgroundflow_webtechno_mapper' => function ($sm) {
                    $mapper = new \PlaygroundFlow\Mapper\WebTechno(
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

                'playgroundflow_user_domain_mapper' => function ($sm) {
                    return new Mapper\UserDomain(
                        $sm->get('doctrine.entitymanager.orm_default')
                    );
                },

                'playgroundflow_prospect_mapper' => function (\Laminas\ServiceManager\ServiceManager $sm) {
                    $mapper = new \PlaygroundFlow\Mapper\Prospect(
                        $sm->get('doctrine.entitymanager.orm_default'),
                        $sm->get('playgroundflow_module_options'),
                        $sm
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
                'playgroundflow_object_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\OpenGraphObject(null, $sm, $translator);
                    $object = new Entity\OpenGraphObject();
                    $form->setInputFilter($object->getInputFilter());

                    return $form;
                },
                'playgroundflow_objectattribute_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\ObjectAttribute(null, $sm, $translator);
                    $objectAttribute = new Entity\OpenGraphObjectAttribute();
                    $form->setInputFilter($objectAttribute->getInputFilter());

                    return $form;
                },
                'playgroundflow_story_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\Story(null, $sm, $translator);
                    $story = new Entity\OpenGraphStory();
                    $form->setInputFilter($story->getInputFilter());

                    return $form;
                },
                'playgroundflow_widget_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\Widget(null, $sm, $translator);
                    $widget = new Entity\OpenGraphWidget();
                    $form->setInputFilter($widget->getInputFilter());

                    return $form;
                },
                'playgroundflow_domain_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\Domain(null, $sm, $translator);
                    $domain = new Entity\OpenGraphDomain();
                    $form->setInputFilter($domain->getInputFilter());

                    return $form;
                },
                'playgroundflow_webtechno_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\WebTechno(null, $sm, $translator);
                    $webtechno = new Entity\OpenGraphWebTechno();
                    $form->setInputFilter($webtechno->getInputFilter());

                    return $form;
                },
                'playgroundflow_storymapping_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\StoryMapping(null, $sm, $translator);
                    $story = new Entity\OpenGraphStoryMapping();
                    $form->setInputFilter($story->getInputFilter());

                    return $form;
                },
                'playgroundflow_objectattributemapping_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\ObjectAttributeMapping(null, $sm, $translator);
                    $attribute = new Entity\OpenGraphObjectAttributeMapping();
                    $form->setInputFilter($attribute->getInputFilter());

                    return $form;
                },
                'playgroundflow_objectmapping_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\ObjectMapping(null, $sm, $translator);
                    $object = new Entity\OpenGraphObjectMapping();
                    $form->setInputFilter($object->getInputFilter());

                    return $form;
                },
            ),
        );
    }
}
