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
            'playgroundflowadmin'        => 'PlaygroundFlow\Controller\AdminController',
        	'playgroundflowadminaction'  => 'PlaygroundFlow\Controller\Admin\ActionController',
        	'playgroundflowadminobject'  => 'PlaygroundFlow\Controller\Admin\ObjectController',
        	'playgroundflowadminstory'   => 'PlaygroundFlow\Controller\Admin\StoryController',
        	'playgroundflowadmindomain'  => 'PlaygroundFlow\Controller\Admin\DomainController',
            'playgroundflow'             => 'PlaygroundFlow\Controller\IndexController',
            'playgroundflowrest'         => 'PlaygroundFlow\Controller\RestController',
            'playgroundflowrestauthent'  => 'PlaygroundFlow\Controller\RestAuthentController',
            'playgroundflowrestsend'     => 'PlaygroundFlow\Controller\RestSendController',
        ),
    ),

    'core_layout' => array(
        'PlaygroundFlow' => array(
            'default_layout' => 'layout/1column',
        	'controllers' => array(
       			'playgroundflowadmin' => array(
      				'default_layout' => 'layout/admin',
     			),
       			'playgroundflowadminaction' => array(
       				'default_layout' => 'layout/admin',
       			),
       			'playgroundflowadminstory' => array(
       				'default_layout' => 'layout/admin',
      			),
     			'playgroundflowadmindomain' => array(
      				'default_layout' => 'layout/admin',
      			),
      			'playgroundflowadminobject' => array(
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
                    									'action'     => 'listAttribute',
                    									'mappingId'     => 0
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
                    																	'action'     => 'listAttribute',
                    															),
                    													),
                    											),
                    											'create' => array(
                    													'type' => 'Segment',
                    													'options' => array(
                    															'route' => '/create/:attributeId',
                    															'defaults' => array(
                    																	'controller' => 'playgroundflowadmindomain',
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
                    																	'controller' => 'playgroundflowadmindomain',
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
                    																	'controller' => 'playgroundflowadmindomain',
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
           									'route' => '/attribute',
        									'defaults' => array(
               									'controller' => 'playgroundflowadminobject',
               									'action'     => 'listAttribute',
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
    					'label'     => 'Liste des stories',
    					'route'     => 'admin/playgroundflow/story',
    					'resource'  => 'flow',
    					'privilege' => 'list',
    				),
    					'create' => array(
    							'label'     => 'Creer une story',
    							'route'     => 'admin/playgroundflow/story/create',
    							'resource'  => 'flow',
    							'privilege' => 'list',
    					),
    					'listactions' => array(
    						'label'     => 'Liste des actions',
    						'route'     => 'admin/playgroundflow/action',
    						'resource'  => 'flow',
    						'privilege' => 'list',
    					),
    					'listobjects' => array(
    						'label'     => 'Liste des objets',
    						'route'     => 'admin/playgroundflow/object',
    						'resource'  => 'flow',
   							'privilege' => 'list',
    					),
    					'listapps' => array(
    							'label'     => 'Liste des domaines',
    							'route'     => 'admin/playgroundflow/domain',
    							'resource'  => 'flow',
    							'privilege' => 'list',
    					),
    					'listdomains' => array(
    							'label'     => 'Liste des apps',
    							'route'     => 'admin/playgroundflow/list',
    							'resource'  => 'flow',
    							'privilege' => 'list',
    					),
    			),
    		),
    	),
    ),
);
