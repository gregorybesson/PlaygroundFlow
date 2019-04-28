<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'playgroundflow_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/Entity'
            ),

            'orm_default' => array(
                'drivers' => array(
                    'PlaygroundFlow\Entity'  => 'playgroundflow_entity'
                )
            )
        )
    ),
    'bjyauthorize' => array(
    
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'flow'          => array(),
            ),
        ),
    
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(array('admin'), 'flow',           array('list','add','edit','delete')),
                ),
            ),
        ),
    
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array( 
                array('controller' => \PlaygroundFlow\Controller\Frontend\IndexController::class,    'roles' => array('guest', 'user')),
                array('controller' => \PlaygroundFlow\Controller\Admin\ActionController::class,      'roles' => array('admin')),
                array('controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,      'roles' => array('admin')),
                array('controller' => \PlaygroundFlow\Controller\Admin\StoryController::class,       'roles' => array('admin')),
                array('controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,      'roles' => array('admin')),
                array('controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,   'roles' => array('admin')),
                array('controller' => \PlaygroundFlow\Controller\Admin\WidgetController::class,      'roles' => array('admin')),
            ),
        ),
    ),
    
    'assetic_configuration' => array(
        'modules' => array(
            'lib' => array(
                'collections' => array(
                    'frontend_mouth_css' => array(
                        'assets' => array(
                            'mouth.css'              => __DIR__ . '/../view/lib/css/mouth.css',
                        ),
                        'filters' => array(),
                        'options' => array(
                            'output' => 'lib/css/mouth'
                        ),
                    ),
                    'frontend_pg' => array(
                        'assets' => array(
                            'json2'             => __DIR__ . '/../view/lib/js/playground/json2.js',
                            'wgxpath.install'   => __DIR__ . '/../view/lib/js/playground/wgxpath.install.js',
                            'pg'                => __DIR__ . '/../view/lib/js/playground/pg.min.js',
                            'pg.connect'        => __DIR__ . '/../view/lib/js/playground/pg.connect.js',
                            'ears.min'          => __DIR__ . '/../view/lib/js/playground/ears.min.js',
                        ),
                        'filters' => array(),
                        'options' => array(
                            'move_raw' => true,
                            'output' => 'lib/js/playground',
                        )
                    ),
                    'frontend_easyxdm' => array(
                        'assets' => array(
                            'easyxdm.min' => __DIR__ . '/../view/lib/js/easyxdm/easyxdm.min.js',
                            'easyxdm'     => __DIR__ . '/../view/lib/js/easyxdm/easyxdm.swf',
                            'json2'       => __DIR__ . '/../view/lib/js/easyxdm/json2.js',
                        ),
                        'options' => array(
                            'move_raw' => true,
                            'output' => 'lib/js/easyxdm',
                        )
                    ),
                ),
            ),
        ),
    
        'routes' => array(
            'frontend.*' => array(
                '@frontend_mouth_css' => '@frontend_mouth_css',
            ),
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view/admin',
        	__DIR__ . '/../view/frontend',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    'controllers' => array(
        'factories' => array(
            \PlaygroundFlow\Controller\IndexController::class => \PlaygroundFlow\Controller\IndexControllerFactory::class,
            \PlaygroundFlow\Controller\RestAuthentController::class => \PlaygroundFlow\Controller\RestAuthentControllerFactory::class,
            \PlaygroundFlow\Controller\RestSendController::class => \PlaygroundFlow\Controller\RestSendControllerFactory::class,
            \PlaygroundFlow\Controller\Frontend\EasyXDMController::class => \PlaygroundFlow\Controller\Frontend\EasyXDMControllerFactory::class,
            \PlaygroundFlow\Controller\Frontend\IndexController::class => \PlaygroundFlow\Controller\Frontend\IndexControllerFactory::class,

            \PlaygroundFlow\Controller\Admin\StoryController::class => \PlaygroundFlow\Controller\Admin\StoryControllerFactory::class,
            \PlaygroundFlow\Controller\Admin\ActionController::class => \PlaygroundFlow\Controller\Admin\ActionControllerFactory::class,
            \PlaygroundFlow\Controller\Admin\WebTechnoController::class => \PlaygroundFlow\Controller\Admin\WebTechnoControllerFactory::class,
            \PlaygroundFlow\Controller\Admin\ObjectController::class => \PlaygroundFlow\Controller\Admin\ObjectControllerFactory::class,
            \PlaygroundFlow\Controller\Admin\WidgetController::class => \PlaygroundFlow\Controller\Admin\WidgetControllerFactory::class,
            \PlaygroundFlow\Controller\Admin\DomainController::class => \PlaygroundFlow\Controller\Admin\DomainControllerFactory::class,
        ),
    ),

    'service_manager' => array(
        'aliases' => array(
            'playgroundflow_doctrine_em' => 'doctrine.entitymanager.orm_default',
        ),
        'factories' => array(
            'playgroundflow_flow_service'         => 'PlaygroundFlow\Service\FlowFactory',
            'playgroundflow_action_service'       => 'PlaygroundFlow\Service\ActionFactory',
            'playgroundflow_object_service'       => 'PlaygroundFlow\Service\OpenGraphObjectFactory',
            'playgroundflow_story_service'        => 'PlaygroundFlow\Service\StoryFactory',
            'playgroundflow_widget_service'       => 'PlaygroundFlow\Service\WidgetFactory',
            'playgroundflow_domain_service'       => 'PlaygroundFlow\Service\DomainFactory',
            'playgroundflow_broadcast_service'    => 'PlaygroundFlow\Service\BroadcastFactory',
            'playgroundflow_storytelling_service' => 'PlaygroundFlow\Service\StoryTellingFactory',
            'playgroundflow_storytelling_listener'=> 'PlaygroundFlow\Service\StoryTellingListenerFactory',
            'playgroundflow_webtechno_service'    => 'PlaygroundFlow\Service\WebTechnoFactory',
            'playgroundflow_user_domain_service'  => 'PlaygroundFlow\Service\UserDomainFactory',
            'playgroundflow_prospect_service'     => 'PlaygroundFlow\Service\ProspectFactory',
        ),
    ),

    'core_layout' => array(
        'frontend' => array(
            'modules' => array(
                'PlaygroundFlow' => array(
                    'default_layout' => 'layout/1column',
                    'controllers' => array(
                        \PlaygroundFlow\Controller\Admin\ActionController::class => array(
                            'default_layout' => 'layout/admin',
                        ),
                        \PlaygroundFlow\Controller\Admin\StoryController::class => array(
                            'default_layout' => 'layout/admin',
                        ),
                        \PlaygroundFlow\Controller\Admin\WidgetController::class => array(
                            'default_layout' => 'layout/admin',
                        ),
                        \PlaygroundFlow\Controller\Admin\DomainController::class => array(
                            'default_layout' => 'layout/admin',
                        ),
                        \PlaygroundFlow\Controller\Admin\ObjectController::class => array(
                            'default_layout' => 'layout/admin',
                        ),
                        \PlaygroundFlow\Controller\Admin\WebTechnoController::class => array(
                            'default_layout' => 'layout/admin',
                        ),
                    ),
                ),
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
            'flowauthent' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/flow/:appId/rest/authent[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => \PlaygroundFlow\Controller\RestAuthentController::class,
                    ),
                ),
            ),
            'flowsend' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/flow/:appId/rest/send[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => \PlaygroundFlow\Controller\RestSendController::class,
                    ),
                ),
            ),
            'flow' => array(
                'type' => 'Zend\Router\Http\Segment',
                'options' => array(
                    'route'    => '/flow[/:appId]',
                    'defaults' => array(
                        'controller' => \PlaygroundFlow\Controller\IndexController::class,
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(
                    'init' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/init',
                            'defaults' => array(
                                'controller' => \PlaygroundFlow\Controller\IndexController::class,
                                'action'     => 'init'
                            ),
                        ),
                    ),
                ),
            ),
            'frontend' => array(
                'child_routes' => array(
                    'easyxdmindex' => array(
                        'type' => 'Zend\Router\Http\Segment',
                        'options' => array(
                            'route'    => 'easyxdm/index',
                            'defaults' => array(
                                'controller' => \PlaygroundFlow\Controller\Frontend\EasyXDMController::class,
                                'action'     => 'index',
                            ),
                        ),
                    ),
            
                    'easyxdmname' => array(
                        'type' => 'Zend\Router\Http\Segment',
                        'options' => array(
                            'route'    => 'easyxdm/name',
                            'defaults' => array(
                                'controller' => \PlaygroundFlow\Controller\Frontend\EasyXDMController::class,
                                'action'     => 'name',
                            ),
                        ),
                    ),
                    
                    'sponsorfriends' => array(
                        'type' => 'Zend\Router\Http\Literal',
                        'options' => array(
                            'route' => 'mon-compte/sponsor-friends',
                            'defaults' => array(
                                'controller' => \PlaygroundFlow\Controller\Frontend\IndexController::class,
                                'action'     => 'sponsorfriends',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'fbshare' => array(
                                'type' => 'Zend\Router\Http\Literal',
                                'options' => array(
                                    'route' => '/fbshare',
                                    'defaults' => array(
                                        'controller' => \PlaygroundFlow\Controller\Frontend\IndexController::class,
                                        'action'     => 'fbshare',
                                    ),
                                ),
                            ),
                            'tweet' => array(
                                'type' => 'Zend\Router\Http\Literal',
                                'options' => array(
                                    'route' => '/tweet',
                                    'defaults' => array(
                                        'controller' => \PlaygroundFlow\Controller\Frontend\IndexController::class,
                                        'action'     => 'tweet',
                                    ),
                                ),
                            ),
                            'google' => array(
                                'type' => 'Zend\Router\Http\Literal',
                                'options' => array(
                                    'route' => '/google',
                                    'defaults' => array(
                                        'controller' => \PlaygroundFlow\Controller\Frontend\IndexController::class,
                                        'action'     => 'google',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'admin' => array(
                'child_routes' => array(
                    'playgroundflow' => array(
                        'type' => 'Zend\Router\Http\Literal',
                        'options' => array(
                            'route' => '/flow',
                            'defaults' => array(
                                'controller' => \PlaygroundFlow\Controller\Admin\ActionController::class,
                                'action'     => 'index',
                            ),
                        ),
                        'child_routes' =>array(
                            'list' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/list/:appId[/:p]',
                                    'defaults' => array(
                                        'controller' => \PlaygroundFlow\Controller\Admin\ActionController::class,
                                        'action'     => 'list',
                                        'appId'     => 0
                                    ),
                                ),
                            ),
                            'action' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/action',
                                    'defaults' => array(
                                        'controller' => \PlaygroundFlow\Controller\Admin\ActionController::class,
                                        'action'     => 'list',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'pagination' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/:p',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\ActionController::class,
                                                'action'     => 'list',
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/create/:actionId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\ActionController::class,
                                                'action'     => 'create',
                                                'actionId'     => 0
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:actionId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\ActionController::class,
                                                'action'     => 'edit',
                                                'actionId'     => 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:actionId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\ActionController::class,
                                                'action'     => 'remove',
                                                'actionId'     => 0
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'story' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/story',
                                    'defaults' => array(
                                        'controller' => \PlaygroundFlow\Controller\Admin\StoryController::class,
                                        'action'     => 'list',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'pagination' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/:p',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\StoryController::class,
                                                'action'     => 'list',
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/create/:storyId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\StoryController::class,
                                                'action'     => 'create',
                                                'storyId'     => 0
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:storyId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\StoryController::class,
                                                'action'     => 'edit',
                                                'storyId'     => 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:storyId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\StoryController::class,
                                                'action'     => 'remove',
                                                'storyId'     => 0
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'widget' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/widget',
                                    'defaults' => array(
                                        'controller' => \PlaygroundFlow\Controller\Admin\WidgetController::class,
                                        'action'     => 'list',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'pagination' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/:p',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\WidgetController::class,
                                                'action'     => 'list',
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/create/:widgetId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\WidgetController::class,
                                                'action'     => 'create',
                                                'storyId'     => 0
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:widgetId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\WidgetController::class,
                                                'action'     => 'edit',
                                                'storyId'     => 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:widgetId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\WidgetController::class,
                                                'action'     => 'remove',
                                                'storyId'     => 0
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'webtechno' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/webtechno',
                                    'defaults' => array(
                                        'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                        'action'     => 'list',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'pagination' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/:p',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                'action'     => 'list',
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/create/:webTechnoId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                'action'     => 'create',
                                                'webTechnoId'     => 0
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:webTechnoId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                'action'     => 'edit',
                                                'webTechnoId'     => 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:webTechnoId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                'action'     => 'remove',
                                                'webTechnoId'     => 0
                                            ),
                                        ),
                                    ),
                                    'story' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/:webTechnoId/story',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                'action'     => 'listStory',
                                                'webTechnoId'     => 0
                                            ),
                                        ),
                                        'may_terminate' => true,
                                        'child_routes' =>array(
                                            'pagination' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/:p',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                        'action'     => 'listStory',
                                                    ),
                                                ),
                                            ),
                                            'create' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/create/:mappingId',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                        'action'     => 'createStory',
                                                        'mappingId'     => 0
                                                    ),
                                                ),
                                            ),
                                            'edit' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/edit/:mappingId',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                        'action'     => 'editStory',
                                                        'mappingId'     => 0
                                                    ),
                                                ),
                                            ),
                                            'remove' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/remove/:mappingId',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                        'action'     => 'removeStory',
                                                        'mappingId'     => 0
                                                    ),
                                                ),
                                            ),
                                            'attribute' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/:mappingId/attribute',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                        'action' => 'listAttribute',
                                                        'mappingId' => 0
                                                    )
                                                ),
                                                'may_terminate' => true,
                                                'child_routes' => array(
                                                    'pagination' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/:p',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                                'action' => 'listAttribute'
                                                            )
                                                        )
                                                    ),
                                                    'create' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/create/:attributeId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                                'action' => 'createAttribute',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                    'edit' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/edit/:attributeId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                                'action' => 'editAttribute',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                    'remove' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/remove/:attributeId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                                'action' => 'removeAttribute',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                ),
                                            ),
                                            'object' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/:mappingId/object',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                        'action' => 'listObject',
                                                        'mappingId' => 0
                                                    )
                                                ),
                                                'may_terminate' => true,
                                                'child_routes' => array(
                                                    'pagination' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/:p',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                                'action' => 'listObject'
                                                            )
                                                        )
                                                    ),
                                                    'create' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/create/:objectId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                                'action' => 'createObject',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                    'edit' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/edit/:objectId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                                'action' => 'editObject',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                    'remove' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/remove/:objectId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\WebTechnoController::class,
                                                                'action' => 'removeObject',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'domain' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/domain',
                                    'defaults' => array(
                                        'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                        'action'     => 'list',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'pagination' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/:p',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                'action'     => 'list',
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/create/:domainId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                'action'     => 'create',
                                                'domainId'     => 0
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:domainId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                'action'     => 'edit',
                                                'domainId'     => 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:domainId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                'action'     => 'remove',
                                                'domainId'     => 0
                                            ),
                                        ),
                                    ),
                                    'story' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/:domainId/story',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                'action'     => 'listStory',
                                                'domainId'     => 0
                                            ),
                                        ),
                                        'may_terminate' => true,
                                        'child_routes' =>array(
                                            'pagination' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/:p',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                        'action'     => 'listStory',
                                                    ),
                                                ),
                                            ),
                                            'create' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/create/:mappingId',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                        'action'     => 'createStory',
                                                        'mappingId'     => 0
                                                    ),
                                                ),
                                            ),
                                            'edit' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/edit/:mappingId',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                        'action'     => 'editStory',
                                                        'mappingId'     => 0
                                                    ),
                                                ),
                                            ),
                                            'remove' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/remove/:mappingId',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                        'action'     => 'removeStory',
                                                        'mappingId'     => 0
                                                    ),
                                                ),
                                            ),
                                            'attribute' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/:mappingId/attribute',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                        'action' => 'listAttribute',
                                                        'mappingId' => 0
                                                    )
                                                ),
                                                'may_terminate' => true,
                                                'child_routes' => array(
                                                    'pagination' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/:p',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                                'action' => 'listAttribute'
                                                            )
                                                        )
                                                    ),
                                                    'create' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/create/:attributeId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                                'action' => 'createAttribute',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                    'edit' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/edit/:attributeId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                                'action' => 'editAttribute',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                    'remove' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/remove/:attributeId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                                'action' => 'removeAttribute',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                ),
                                            ),
                                            'object' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/:mappingId/object',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                        'action' => 'listObject',
                                                        'mappingId' => 0
                                                    )
                                                ),
                                                'may_terminate' => true,
                                                'child_routes' => array(
                                                    'pagination' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/:p',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                                'action' => 'listObject'
                                                            )
                                                        )
                                                    ),
                                                    'create' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/create/:objectId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                                'action' => 'createObject',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                    'edit' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/edit/:objectId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                                'action' => 'editObject',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                    'remove' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/remove/:objectId',
                                                            'defaults' => array(
                                                                'controller' => \PlaygroundFlow\Controller\Admin\DomainController::class,
                                                                'action' => 'removeObject',
                                                                'attributeId' => 0
                                                            )
                                                        )
                                                    ),
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'object' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/object',
                                    'defaults' => array(
                                        'controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,
                                        'action'     => 'list',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'pagination' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                        'route' => '/:p',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,
                                                'action'     => 'list',
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/create/:objectId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,
                                                'action'     => 'create',
                                                'objectId'     => 0
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:objectId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,
                                                'action'     => 'edit',
                                                'objectId'     => 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:objectId',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,
                                                'action'     => 'remove',
                                                'objectId'     => 0
                                            ),
                                        ),
                                    ),
                                    'attribute' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/:objectId/attribute',
                                            'defaults' => array(
                                                'controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,
                                                'action'     => 'listAttribute',
                                                'objectId'   => 0
                                                
                                            ),
                                        ),
                                        'may_terminate' => true,
                                        'child_routes' =>array(
                                            'pagination' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/:p',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,
                                                        'action'     => 'listAttribute',
                                                    ),
                                                ),
                                            ),
                                            'create' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/create/:attributeId',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,
                                                        'action'     => 'createAttribute',
                                                        'attributeId'     => 0
                                                    ),
                                                ),
                                            ),
                                            'get' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/get/:attributeId',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,
                                                        'action'     => 'getAttribute',
                                                        'attributeId'     => 0
                                                    ),
                                                ),
                                            ),
                                            'edit' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/edit/:attributeId',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,
                                                        'action'     => 'editAttribute',
                                                        'attributeId'     => 0
                                                    ),
                                                ),
                                            ),
                                            'remove' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/remove/:attributeId',
                                                    'defaults' => array(
                                                        'controller' => \PlaygroundFlow\Controller\Admin\ObjectController::class,
                                                        'action'     => 'removeAttribute',
                                                        'attributeId'     => 0
                                                    ),
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'translator' => array(
            'locale' => 'fr_FR',
            'translation_file_patterns' => array(
                    array(
                            'type'         => 'phpArray',
                            'base_dir'     => __DIR__ . '/../language',
                            'pattern'      => '%s.php',
                            'text_domain'  => 'playgroundflow'
                    ),
            ),
    ),

    'navigation' => array(
        'admin' => array(
            'playgroundflow'     => array(
                'label'     => 'Open Graph',
                'route'     => 'admin/playgroundflow/story',
                'resource'  => 'flow',
                'privilege' => 'list',
                'target' => 'nav-icon icon-share',
                'pages' => array(
                    'list' => array(
                        'label'     => 'Stories list',
                        'route'     => 'admin/playgroundflow/story',
                        'resource'  => 'flow',
                        'privilege' => 'list',
                    ),
                    'create' => array(
                        'label'     => 'Create story',
                        'route'     => 'admin/playgroundflow/story/create',
                        'resource'  => 'flow',
                        'privilege' => 'list',
                    ),
                    'listactions' => array(
                        'label'     => 'Actions list',
                        'route'     => 'admin/playgroundflow/action',
                        'resource'  => 'flow',
                        'privilege' => 'list',
                    ),
                    'listobjects' => array(
                        'label'     => 'Objects list',
                        'route'     => 'admin/playgroundflow/object',
                        'resource'  => 'flow',
                        'privilege' => 'list',
                    ),
                    'listapps' => array(
                        'label'     => 'Domains list',
                        'route'     => 'admin/playgroundflow/domain',
                        'resource'  => 'flow',
                        'privilege' => 'list',
                    ),
                    'listwidgets' => array(
                        'label'     => 'Widgets list',
                        'route'     => 'admin/playgroundflow/widget',
                        'resource'  => 'flow',
                        'privilege' => 'list',
                    ),
                    'listwebtechnos' => array(
                        'label'     => 'WebTechnos list',
                        'route'     => 'admin/playgroundflow/webtechno',
                        'resource'  => 'flow',
                        'privilege' => 'list',
                    ),
                ),
            ),
        ),
    ),
);
