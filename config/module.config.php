<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'playgroundflow_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/PlaygroundFlow/Entity'
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
                  
                array('controller' => 'PlaygroundFlow\Controller\Frontend\Index', 'roles' => array('guest', 'user')),
                
                // Admin area
                array('controller' => 'playgroundflowadminaction',      'roles' => array('admin')),
                array('controller' => 'playgroundflowadminobject',      'roles' => array('admin')),
                array('controller' => 'playgroundflowadminstory',       'roles' => array('admin')),
                array('controller' => 'playgroundflowadmindomain',      'roles' => array('admin')),
                array('controller' => 'playgroundflowadminwebtechno',   'roles' => array('admin')),
                array('controller' => 'playgroundflowadminwidget',      'roles' => array('admin')),
            ),
        ),
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
                // Admin area
                array('controller' => 'playgroundflowadminaction',                              'roles' => array('admin')),
                array('controller' => 'playgroundflowadminobject',                              'roles' => array('admin')),
                array('controller' => 'playgroundflowadminstory',                               'roles' => array('admin')),
                array('controller' => 'playgroundflowadmindomain',                              'roles' => array('admin')),
                array('controller' => 'playgroundflowadminwebtechno',                           'roles' => array('admin')),
                array('controller' => 'playgroundflowadminwidget',                              'roles' => array('admin')),
            ),
        ),
    ),
    
    'assetic_configuration' => array(
        'modules' => array(
            'lib_flow' => array(
                'root_path' => array(
                    __DIR__ . '/../view/lib',
                ),
                'collections' => array(
                    'frontend_mouth_css' => array(
                        'assets' => array(
                            'mouth.css'              => 'css/mouth.css',
                        ),
                        'filters' => array(),
                        'options' => array(
                            'output' => 'lib/css/mouth'
                        ),
                    ),
                    'frontend_pg' => array(
                        'assets' => array(
                            'json2'             => 'js/playground/json2.js',
                            'wgxpath.install'   => 'js/playground/wgxpath.install.js',
                            'pg'                => 'js/playground/pg.min.js',
                            'pg.connect'        => 'js/playground/pg.connect.js',
                            'ears.min'          => 'js/playground/ears.min.js',
                        ),
                        'filters' => array(),
                        'options' => array(
                            'move_raw' => true,
                            'output' => 'lib',
                        )
                    ),
                    'frontend_easyxdm' => array(
                        'assets' => array(
                            'easyxdm.min' => 'js/easyxdm/easyxdm.min.js',
                            'easyxdm'     => 'js/easyxdm/easyxdm.swf',
                            'json2'       => 'js/easyxdm/json2.js',
                        ),
                        'options' => array(
                            'move_raw' => true,
                            'output' => 'lib',
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
        'invokables' => array(
        	'playgroundflowadminaction'     => 'PlaygroundFlow\Controller\Admin\ActionController',
        	'playgroundflowadminobject'     => 'PlaygroundFlow\Controller\Admin\ObjectController',
        	'playgroundflowadminstory'      => 'PlaygroundFlow\Controller\Admin\StoryController',
            'playgroundflowadminwidget'     => 'PlaygroundFlow\Controller\Admin\WidgetController',
        	'playgroundflowadmindomain'     => 'PlaygroundFlow\Controller\Admin\DomainController',
            'playgroundflowadminwebtechno'  => 'PlaygroundFlow\Controller\Admin\WebTechnoController',
            'playgroundflow'                => 'PlaygroundFlow\Controller\IndexController',
            'playgroundflowrestauthent'     => 'PlaygroundFlow\Controller\RestAuthentController',
            'playgroundflowrestsend'        => 'PlaygroundFlow\Controller\RestSendController',
            'playgroundfloweasyxdm'         => 'PlaygroundFlow\Controller\Frontend\EasyXDMController',
            'PlaygroundFlow\Controller\Frontend\Index' => 'PlaygroundFlow\Controller\Frontend\IndexController'
        ),
    ),

    'core_layout' => array(
        'PlaygroundFlow' => array(
            'default_layout' => 'layout/1column',
        	'controllers' => array(
       			'playgroundflowadminaction' => array(
       				'default_layout' => 'layout/admin',
       			),
       			'playgroundflowadminstory' => array(
       				'default_layout' => 'layout/admin',
      			),
        	    'playgroundflowadminwidget' => array(
        	        'default_layout' => 'layout/admin',
        	    ),
     			'playgroundflowadmindomain' => array(
      				'default_layout' => 'layout/admin',
      			),
      			'playgroundflowadminobject' => array(
      				'default_layout' => 'layout/admin',
       			),
                'playgroundflowadminwebtechno' => array(
                    'default_layout' => 'layout/admin',
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
                        'controller' => 'playgroundflowrestauthent',
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
                        'controller' => 'playgroundflowrestsend',
                    ),
                ),
            ),
            'flow' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/flow[/:appId]',
                    'defaults' => array(
                        'controller' => 'playgroundflow',
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
                                'controller' => 'playgroundflow',
                                'action'     => 'init'
                            ),
                        ),
                    ),
                ),
            ),
            'frontend' => array(
                'child_routes' => array(
                    'easyxdmindex' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => 'easyxdm/index',
                            'defaults' => array(
                                'controller' => 'playgroundfloweasyxdm',
                                'action'     => 'index',
                            ),
                        ),
                    ),
            
                    'easyxdmname' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => 'easyxdm/name',
                            'defaults' => array(
                                'controller' => 'playgroundfloweasyxdm',
                                'action'     => 'name',
                            ),
                        ),
                    ),
                    
                    'sponsorfriends' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'mon-compte/sponsor-friends',
                            'defaults' => array(
                                'controller' => 'PlaygroundFlow\Controller\Frontend\Index',
                                'action'     => 'sponsorfriends',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'fbshare' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/fbshare',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundFlow\Controller\Frontend\Index',
                                        'action'     => 'fbshare',
                                    ),
                                ),
                            ),
                            'tweet' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/tweet',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundFlow\Controller\Frontend\Index',
                                        'action'     => 'tweet',
                                    ),
                                ),
                            ),
                            'google' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/google',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundFlow\Controller\Frontend\Index',
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
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/flow',
                            'defaults' => array(
                                'controller' => 'playgroundflowadminaction',
                                'action'     => 'index',
                            ),
                        ),
                    	'child_routes' =>array(
                  			'list' => array(
               					'type' => 'Segment',
               					'options' => array(
              						'route' => '/list/:appId[/:p]',
            						'defaults' => array(
          								'controller' => 'playgroundflowadminaction',
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
                   						'controller' => 'playgroundflowadminaction',
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
                    							'controller' => 'playgroundflowadminaction',
               									'action'     => 'list',
                    						),
                    					),
                   					),
                    				'create' => array(
                    					'type' => 'Segment',
                    					'options' => array(
           									'route' => '/create/:actionId',
                							'defaults' => array(
              	     							'controller' => 'playgroundflowadminaction',
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
                    							'controller' => 'playgroundflowadminaction',
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
                    							'controller' => 'playgroundflowadminaction',
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
                    					'controller' => 'playgroundflowadminstory',
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
                    							'controller' => 'playgroundflowadminstory',
                    							'action'     => 'list',
       										),
                    					),
                    				),
                    				'create' => array(
                    					'type' => 'Segment',
                    					'options' => array(
                    						'route' => '/create/:storyId',
                    						'defaults' => array(
                    							'controller' => 'playgroundflowadminstory',
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
                    							'controller' => 'playgroundflowadminstory',
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
                    							'controller' => 'playgroundflowadminstory',
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
                    	                'controller' => 'playgroundflowadminwidget',
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
      										    'controller' => 'playgroundflowadminwidget',
      										    'action'     => 'list',
      										),
                    	                ),
                    	            ),
                    	            'create' => array(
                    	                'type' => 'Segment',
                    	                'options' => array(
                    	                    'route' => '/create/:widgetId',
                    	                    'defaults' => array(
                    	                        'controller' => 'playgroundflowadminwidget',
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
                    	                        'controller' => 'playgroundflowadminwidget',
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
                    	                        'controller' => 'playgroundflowadminwidget',
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
                                        'controller' => 'playgroundflowadminwebtechno',
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
                                                'controller' => 'playgroundflowadminwebtechno',
                                                'action'     => 'list',
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/create/:webTechnoId',
                                            'defaults' => array(
                                                'controller' => 'playgroundflowadminwebtechno',
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
                                                'controller' => 'playgroundflowadminwebtechno',
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
                                                'controller' => 'playgroundflowadminwebtechno',
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
                                                'controller' => 'playgroundflowadminwebtechno',
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
                                                        'controller' => 'playgroundflowadminwebtechno',
                                                        'action'     => 'listStory',
                                                    ),
                                                ),
                                            ),
                                            'create' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/create/:mappingId',
                                                    'defaults' => array(
                                                        'controller' => 'playgroundflowadminwebtechno',
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
                                                        'controller' => 'playgroundflowadminwebtechno',
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
                                                        'controller' => 'playgroundflowadminwebtechno',
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
                                                        'controller' => 'playgroundflowadminwebtechno',
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
                                                                'controller' => 'playgroundflowadminwebtechno',
                                                                'action' => 'listAttribute'
                                                            )
                                                        )
                                                    ),
                                                    'create' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/create/:attributeId',
                                                            'defaults' => array(
                                                                'controller' => 'playgroundflowadminwebtechno',
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
                                                                'controller' => 'playgroundflowadminwebtechno',
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
                                                                'controller' => 'playgroundflowadminwebtechno',
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
                                                        'controller' => 'playgroundflowadminwebtechno',
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
                                                                'controller' => 'playgroundflowadminwebtechno',
                                                                'action' => 'listObject'
                                                            )
                                                        )
                                                    ),
                                                    'create' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/create/:objectId',
                                                            'defaults' => array(
                                                                'controller' => 'playgroundflowadminwebtechno',
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
                                                                'controller' => 'playgroundflowadminwebtechno',
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
                                                                'controller' => 'playgroundflowadminwebtechno',
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
                    					'controller' => 'playgroundflowadmindomain',
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
                    							'controller' => 'playgroundflowadmindomain',
                    							'action'     => 'list',
                    						),
               							),
           							),
                    				'create' => array(
                    					'type' => 'Segment',
                    					'options' => array(
                    						'route' => '/create/:domainId',
                    						'defaults' => array(
                    							'controller' => 'playgroundflowadmindomain',
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
   												'controller' => 'playgroundflowadmindomain',
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
   												'controller' => 'playgroundflowadmindomain',
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
                    							'controller' => 'playgroundflowadmindomain',
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
           												'controller' => 'playgroundflowadmindomain',
                    									'action'     => 'listStory',
                    								),
               									),
                    						),
                    						'create' => array(
                    							'type' => 'Segment',
               									'options' => array(
                    								'route' => '/create/:mappingId',
                    								'defaults' => array(
          												'controller' => 'playgroundflowadmindomain',
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
                    									'controller' => 'playgroundflowadmindomain',
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
                    									'controller' => 'playgroundflowadmindomain',
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
                                                        'controller' => 'playgroundflowadmindomain',
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
                                                                'controller' => 'playgroundflowadmindomain',
                                                                'action' => 'listAttribute'
                                                            )
                                                        )
                                                    ),
                                                    'create' => array(
                                                        'type' => 'Segment',
                                                        'options' => array(
                                                            'route' => '/create/:attributeId',
                                                            'defaults' => array(
                                                                'controller' => 'playgroundflowadmindomain',
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
                                                                'controller' => 'playgroundflowadmindomain',
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
                                                                'controller' => 'playgroundflowadmindomain',
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
                    					                'controller' => 'playgroundflowadmindomain',
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
                    					                        'controller' => 'playgroundflowadmindomain',
                    					                        'action' => 'listObject'
                    					                    )
                    					                )
                    					            ),
                    					            'create' => array(
                    					                'type' => 'Segment',
                    					                'options' => array(
                    					                    'route' => '/create/:objectId',
                    					                    'defaults' => array(
                    					                        'controller' => 'playgroundflowadmindomain',
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
                    					                        'controller' => 'playgroundflowadmindomain',
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
                    					                        'controller' => 'playgroundflowadmindomain',
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
          								'controller' => 'playgroundflowadminobject',
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
                    							'controller' => 'playgroundflowadminobject',
                    							'action'     => 'list',
                    						),
                   						),
               						),
           							'create' => array(
               							'type' => 'Segment',
              							'options' => array(
                   							'route' => '/create/:objectId',
                   							'defaults' => array(
                   								'controller' => 'playgroundflowadminobject',
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
                   								'controller' => 'playgroundflowadminobject',
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
               									'controller' => 'playgroundflowadminobject',
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
               									'controller' => 'playgroundflowadminobject',
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
               											'controller' => 'playgroundflowadminobject',
														'action'     => 'listAttribute',
               										),
               									),
               								),
               								'create' => array(
               									'type' => 'Segment',
               									'options' => array(
               										'route' => '/create/:attributeId',
               										'defaults' => array(
               											'controller' => 'playgroundflowadminobject',
														'action'     => 'createAttribute',
               											'attributeId'     => 0
               										),
       											),
        									),
               								'edit' => array(
               									'type' => 'Segment',
               									'options' => array(
               										'route' => '/edit/:attributeId',
               										'defaults' => array(
               											'controller' => 'playgroundflowadminobject',
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
               											'controller' => 'playgroundflowadminobject',
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
