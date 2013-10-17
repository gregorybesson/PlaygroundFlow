<?php

namespace PlaygroundFlow\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
 
class RestAuthentController extends AbstractRestfulController
{
	/**
	 * @var DomainService
	 */
	protected $adminDomainService;
	
    /*
     * http://127.0.0.1/playground/flow/XX-XX-YY/rest/authent
    */
    public function getList()
    {
    	$service 	= $this->getAdminDomainService();
    	$appId = $this->getEvent()->getRouteMatch()->getParam('appId');
    	$service 	= $this->getAdminDomainService();
    	$appId = $this->getEvent()->getRouteMatch()->getParam('appId');
    	$uri = $this->getRequest()->getUri();
    	$base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
    	$domain = $service->getDomainMapper()->findOneBy(array('domain' => $base));
    	
    	
    	$stories = array();
    	
    	if($domain){
        	$storymappings = $domain->getStoryMappings();
        	foreach($storymappings as $sm){
        		$conditions = array();
        		$events = array('before'=>array(), 'after'=>array());
        		$objects = array();
    
        		foreach($sm->getObjects() as $objectMapping){
        		    $objects['id'] = $objectMapping->getObject()->getCode();
        		    $attributes = array();
        		    foreach($objectMapping->getAttributes() as $attributeMapping){
        		        $attributes[] = array('name' => $attributeMapping->getAttribute()->getCode(), 'xpath' => $attributeMapping->getXpath());
        		    }
        		    $objects['properties'] = $attributes;
        		}
        		
        		if($sm->getConditionsUrl()!=''){
        			$conditions['url'] = $sm->getConditionsUrl();
        		}
        		
        		if($sm->getConditionsXpath()!=''){
        			$conditions['xpath'] = $sm->getConditionsXpath();
        		}
        		
        		if($sm->getEventBeforeUrl()!=''){
        			$events['before']['url'] = $sm->getEventBeforeUrl();
        		}
        		
        		if($sm->getEventBeforeXpath()!=''){
        			$events['before']['xpath'] = $sm->getEventBeforeXpath();
        		}
        		
        		if($sm->getEventAfterUrl()!=''){
        			$events['after']['url'] = $sm->getEventAfterUrl();
        		}
        		
        		if($sm->getEventAfterXpath()!=''){
        			$events['after']['xpath'] = $sm->getEventAfterXpath();
        		}
        		
        		$stories[$sm->getStory()->getCode()] = array(
        			'action' => $sm->getStory()->getAction()->getCode(),
        			'events' => $events,
        			'conditions' => $conditions,
        			'objects' => $objects,
        		);
        	}
    	}
    	
        $response = $this->getResponse();
        $contentType = 'application/json';
        $adapter = '\Zend\Serializer\Adapter\Json';
        $response->getHeaders()->addHeaderLine('Content-Type',$contentType);
        $adapter = new $adapter;

        $content = array(
       		'library' => array(
   				'config' => array(
        			'broadcast' => false
       			),
       			'stories' => $stories,
       		)
        );
        
        $response->setContent($adapter->serialize($content));
        
        return $response;
    }
 
    /*
     * http://127.0.0.1/playground/flow/XX-XX-YY/rest/authent/9
     */
    public function get($id)
    {
        /*$response = $this->getResponse();
        $contentType = 'application/json';
        $adapter = '\Zend\Serializer\Adapter\Json';
        $response->getHeaders()->addHeaderLine('Content-Type',$contentType);
        $adapter = new $adapter;
        
        $content = array('data' => 'grg2 was there');
        $response->setContent($adapter->serialize($content));
        
        return $response;*/
    	$response = $this->getResponse();
        $contentType = 'application/json';
        $adapter = '\Zend\Serializer\Adapter\Json';
        $response->getHeaders()->addHeaderLine('Content-Type',$contentType);
        $adapter = new $adapter;
        
        $appId = $this->getEvent()->getRouteMatch()->getParam('appId');
        
        /*
        $content = array(
            'login' => array(
                'urls' => array(
                    'page' => 'http://ic.adfab.fr/pmagento/index.php/customer/account/login/',
                    'success' => 'http://ic.adfab.fr/pmagento/index.php/customer/account/',
                ),
                'items' => array(
                    array('selector' => 'id', 'name' => 'email')
                ),
            ),
            'logout' => array(
                'urls' => array(
                    'page' => 'http://ic.adfab.fr/pmagento/',
                    'success' => 'http://ic.adfab.fr/pmagento/index.php/customer/account/logoutSuccess/',
                ),
            ),
            'taxonomy' => array(
                'config' => array(
                    'broadcast' => false,
                ),
                'items' => array(
                    array(
                        'url' => '/account', 
                        'xpath' => "//div[@class='block-account']",
                        'id' =>  'account'
                    ),
                    array(
                        'url' => '/checkout',
                        'id' =>  'checkout'
                    ),
                    array(
                        'xpath' => "//div[@class='my-wishlist']",
                        'id' =>  'wishlist'
                    ),
                ),
            ),
        );*/

        $content = array(
	        'library' => array(
	        	'config' => array(
	        		'broadcast' => false
	        	),
	        	'stories' => array(
	        		'login_user' => array(
	        			'events' => array(
	        				'before' => array(
	        					'url' => '/customer\\/account\\/login/',
	        					'xpath' => "//a[@title='Log In']"
	        				),
	        				'after' => array(
	                        	'url' => '/customer\\/account/',
	                            'xpath' => "//a[@title='Log Out']"
	        				),
	        			),
	        			'conditions' => array(
	        				'url' => '/customer\\/account/',
	        				'xpath' => "//a[@title='Log Out']"
	        			),
	        			'action' => 'login',
	                	'objects' => array(
	        				'id'=> 'login_id',
	        				'properties'=> array(
	        					array(
	        						'name'=> 'email',
	        						'xpath'=> "//input[@id='email']"
	        					)
	        				),
	                	),
	        		),
	        		'logout_user' => array(
	        			'events'=> array(
	        				'before'=> array(
	        					'url'=> '/ic.adfab.fr\/pmagento/',
	        					'xpath'=> "//a[@title='Log Out']"
	        				),
	        				'after'=> array(
	        					'url'=> '/ic.adfab.fr\/pmagento/',
	        					'xpath'=> "//a[@title='Log In']"
	        				),
	        			),
	                	'conditions'=> array(
	        				'url'=> '/logoutSuccess/',
	        				'xpath'=> "//a[@title='Log In']"
	        			),
	        			'action'=> 'logout'
	        		),
	        		'tips1' => array(
	                	'conditions'=> array(
	        				'url'=> '/ic.adfab.fr\/pmagento/'
	        			),
	        			'action'=> 'find',
	        			'objects'=> array(
	        				'id'=> 'tip 1'
	        			),
	        			'event'=> array(
	        				'xpath'=>'//body',
	        				'type'=>'mouseup',
							'area'=> array(
								'debug'=>true,
	        					'y'=>200,
	        					'x'=>200,
	       						'text'=>'null',
	   							'width'=>400,
								'height'=>200,
								'xpath'=>"//p[@class='home-callout'][1]/img/@src"
							),
	        			),
	        		),
	        			'tips2' => array(
	        					'conditions'=> array(
	        							'url'=> '/ic.adfab.fr\/pmagento/'
	        					),
	        					'action'=> 'find',
	        					'objects'=> array(
	        							'id'=> 'tip 2'
	        					),
	        					'event'=> array(
	        							'xpath'=>'//body',
	        							'type'=>'mouseup',
	        							'area'=> array(
	        									'debug'=>true,
	        									'y'=>null,
	        									'x'=>null,
	        									'text'=>'Your',
	        									'width'=>null,
	        									'height'=>null,
	        									'xpath'=>"//html/body[@class=' cms-index-index cms-home']/div[@class='wrapper']/div[@class='page']/div[@class='header-container']/div[@class='header']/div[@class='quick-access']/div[@class='form-language']/label"
	        							),
	        					),
	        			),
				),
	        )
	    );
        				
        $response->setContent($adapter->serialize($content));
        
        return $response;
    }
    
    public function create($data)
    {
        $service 	= $this->getAdminDomainService();
    	$appId = $this->getEvent()->getRouteMatch()->getParam('appId');
    	$domain = $service->getDomainMapper()->findById(2);
    	
    	$storymappings = $domain->getStoryMappings();
    	$stories = array();
    	
    	foreach($storymappings as $sm){
    		$conditions = array();
    		$events = array('before'=>array(), 'after'=>array());
    		$objects = array();

    		foreach($sm->getObjects() as $objectMapping){
    		    $objects['id'] = $objectMapping->getObject()->getCode();
    		    $attributes = array();
    		    foreach($objectMapping->getAttributes() as $attributeMapping){
    		        $attributes[] = array('name' => $attributeMapping->getAttribute()->getCode(), 'xpath' => $attributeMapping->getXpath());
    		    }
    		    $objects['properties'] = $attributes;
    		}
    		
    		if($sm->getConditionsUrl()!=''){
    			$conditions['url'] = $sm->getConditionsUrl();
    		}
    		
    		if($sm->getConditionsXpath()!=''){
    			$conditions['xpath'] = $sm->getConditionsXpath();
    		}
    		
    		if($sm->getEventBeforeUrl()!=''){
    			$events['before']['url'] = $sm->getEventBeforeUrl();
    		}
    		
    		if($sm->getEventBeforeXpath()!=''){
    			$events['before']['xpath'] = $sm->getEventBeforeXpath();
    		}
    		
    		if($sm->getEventAfterUrl()!=''){
    			$events['after']['url'] = $sm->getEventAfterUrl();
    		}
    		
    		if($sm->getEventAfterXpath()!=''){
    			$events['after']['xpath'] = $sm->getEventAfterXpath();
    		}
    		
    		$stories[$sm->getStory()->getCode()] = array(
    			'action' => $sm->getStory()->getAction()->getCode(),
    			'events' => $events,
    			'conditions' => $conditions,
    			'objects' => $objects,
    		);
    	}
    	
        $response = $this->getResponse();
        $contentType = 'application/json';
        $adapter = '\Zend\Serializer\Adapter\Json';
        $response->getHeaders()->addHeaderLine('Content-Type',$contentType);
        $adapter = new $adapter;

        $content = array(
       		'library' => array(
   				'config' => array(
        			'broadcast' => false
       			),
       			'stories' => $stories,
       		)
        );
        
        $response->setContent($adapter->serialize($content));
        
        return $response;
    }
 
    public function createold($data)
    {
        $response = $this->getResponse();
        $contentType = 'application/json';
        $adapter = '\Zend\Serializer\Adapter\Json';
        $response->getHeaders()->addHeaderLine('Content-Type',$contentType);
        $adapter = new $adapter;
        
        $appId = $this->getEvent()->getRouteMatch()->getParam('appId');

        $content = array(
	        'library' => array(
	        	'config' => array(
	        		'broadcast' => false
	        	),
	        	'stories' => array(
	        		'login_user' => array(
	        			'events' => array(
	        				'before' => array(
	        					'url' => '/customer\\/account\\/login/',
	        					'xpath' => "//a[@title='Log In']"
	        				),
	        				'after' => array(
	                        	'url' => '/customer\\/account/',
	                            'xpath' => "//a[@title='Log Out']"
	        				),
	        			),
	        			'conditions' => array(
	        				'url' => '/customer\\/account/',
	        				'xpath' => "//a[@title='Log Out']"
	        			),
	        			'action' => 'login',
	                	'objects' => array(
	        				'id'=> 'login_id',
	        				'properties'=> array(
	        					array(
	        						'name'=> 'email',
	        						'xpath'=> "//input[@id='email']"
	        					)
	        				),
	                	),
	        		),
	        	    'grg' => array(
	        	        'events' => array(
	        	            'before' => array(
	        	                'url' => '/about-magento-demo-store',
	        	                'xpath' => "//input[@id='search']",
	        	            ),
	        	            'after' => array(
	        	                'url' => '/about-magento-demo-store',
	        	                'xpath' => "//input[@id='search']",
	        	            ),
	        	        ),
	        	        'conditions' => array(
	        	            'url' => 'http://pmagento.local/about-magento-demo-store',
	        	            'xpath' => "//input[@id='search']"
	        	        ),
	        	        'action' => 'grg',
	        	        'objects' => array(
	        	            'id'=> 'ggread',
	        	            'properties'=> array(
	        	                array(
	        	                    'name'=> 'ggr',
	        	                    'xpath'=> "//div[@class='col-2']/p[1]/strong"
	        	                )
	        	            ),
	        	        ),
	        	    ),
	        		'logout_user' => array(
	        			'events'=> array(
	        				'before'=> array(
	        					'url'=> '/ic.adfab.fr\/pmagento/',
	        					'xpath'=> "//a[@title='Log Out']"
	        				),
	        				'after'=> array(
	        					'url'=> '/ic.adfab.fr\/pmagento/',
	        					'xpath'=> "//a[@title='Log In']"
	        				),
	        			),
	                	'conditions'=> array(
	        				'url'=> '/logoutSuccess/',
	        				'xpath'=> "//a[@title='Log In']"
	        			),
	        			'action'=> 'logout'
	        		),
	        		'tips1' => array(
	                	'conditions'=> array(
	        				'url'=> '/ic.adfab.fr\/pmagento/'
	        			),
	        			'action'=> 'find',
	        			'objects'=> array(
	        				'id'=> 'tip 1'
	        			),
	        			'event'=> array(
	        				'xpath'=>'//body',
	        				'type'=>'mouseup',
							'area'=> array(
								'debug'=>false,
	        					'y'=>7,
	        					'x'=>8,
	       						'text'=>'null',
	   							'width'=>63,
								'height'=>32,
								'xpath'=>"//html[1]/body[1]/div[1]/div[1]/div[1]/div[1]/h1[1]/a[1]/img[1]"
							),
	        			),
	        		),
	        		'tips2' => array(
	        			'conditions'=> array(
	        				'url'=> '/ic.adfab.fr\/pmagento/'
	        			),
	        			'action'=> 'find',
	        			'objects'=> array(
	       					'id'=> 'tip 2'
	       				),
	       				'event'=> array(
	   						'xpath'=>'//body',
	       					'type'=>'mouseup',
	       					'area'=> array(
	       						'debug'=>false,
	       						'y'=>null,
        						'x'=>null,
	       						'text'=>'Your',
	      						'width'=>null,
	      						'height'=>null,
	       						'xpath'=>"//html/body[@class=' cms-index-index cms-home']/div[@class='wrapper']/div[@class='page']/div[@class='header-container']/div[@class='header']/div[@class='quick-access']/div[@class='form-language']/label"
	       					),
	        			),
	        		),
				),
	        )
	    );
        				
        $response->setContent($adapter->serialize($content));
        
        return $response;
    }
 
    public function update($id, $data)
    {
        # code...
    }
 
    public function delete($id)
    {
        # code...
    }
    
    public function getAdminDomainService()
    {
    	if (!$this->adminDomainService) {
    		$this->adminDomainService = $this->getServiceLocator()->get('playgroundflow_domain_service');
    	}
    
    	return $this->adminDomainService;
    }
    
    public function setAdminDomainService(AdminDomainService $adminDomainService)
    {
    	$this->adminDomainService = $adminDomainService;
    
    	return $this;
    }
}
