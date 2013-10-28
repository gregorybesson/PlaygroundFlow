<?php

namespace PlaygroundFlow\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
 
class RestSendController extends AbstractRestfulController
{
	/**
	 * @var GameService
	 */
	protected $storytellingService;
	
	protected $domainService;
	
    public function getList()
    {
        $response = $this->getResponse();
        
        $contentType = 'application/json';
        $adapter = '\Zend\Serializer\Adapter\Json';
        $response->getHeaders()->addHeaderLine('Content-Type',$contentType);
        $adapter = new $adapter;
        
        $data = '{"user":{"anonymous":"88m766k11323f515p621"},"objects":{"id":"login_id"},"action":"login","url":"http://pmagento.local/customer/account/","apiKey":"key_first"}';
        $data = json_decode($data, true);
        $content = array(
            'result' => array(
                'message' => 'No event detected',
                'success' => false,
                'data' => $data,
            ),
        );
        
        $response->setContent($adapter->serialize($content));
        
        return $response ;
    }
 
    public function get($id)
    {
       return;
    }
 
    /*
     * curl -i -H "Accept: application/json" -X POST -d "url=test&title=titre" http://127.0.0.1/playground/flow/XX-XX-YY-XX/rest/send
     */
    public function create($data)
    {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	$storyTellingService = $this->getStorytellingService();
    	$domainService = $this->getDomainService();
    	
    	$data = $this->fromJson();
    	
    	// TODO : replace the following by the next one once ears ready
    	$storyMappingId = $data['action'];
    	//$storyMappingId = $data['story_mapping_id'];
    	$storyMapping = $domainService->getStoryMappingMapper()->findById($storyMappingId);
    	
    	if (! $storyMapping) {
    	    $content = array(
        	    'result' => array(
        	        'message' => 'Story missing',
        	        'success' => false,
        	        'data' => null,
        	    ),
        	);
        	
        	$response->setContent($adapter->serialize($content));
        	return $response;
    	}
    	
    	$storyTelling = new \PlaygroundFlow\Entity\OpenGraphStoryTelling();
    	$storyTelling->setOpenGraphStoryMapping($storyMapping);
    	$storyTelling->setUser(null);
    	$storyTelling->setObject(json_encode($data['objects']));
    	$storyTelling->setPoints($storyMapping->getPoints());
    	$storyTelling->setSecretKey(null);
    	$storyTellingService->getStoryTellingMapper()->insert($storyTelling);
    	
    	$storyTellingService->tellStory($storyMapping, $storyTelling, $data);
    	
    	$this->getEventManager()->trigger('story.'.$storyMapping->getId() , $this, array('storyTelling' => $storyTelling));
    	
    	$contentType = 'application/json';
    	$adapter = '\Zend\Serializer\Adapter\Json';
    	$response->getHeaders()->addHeaderLine('Content-Type',$contentType);
    	$adapter = new $adapter;
    	
    	$data = $this->fromJson();
    	$content = array(
    	    'result' => array(
    	        'message' => 'Post detected',
    	        'success' => true,
    	        'data' => $data['objects'],
    	    ),
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
    
    public function fromJson() {
    	$body = $this->getRequest()->getContent();
    	if (!empty($body)) {
    		$json = json_decode($body, true);
    		if (!empty($json)) {
    			return $json;
    		}
    	}
    
    	return false;
    }
    
    public function getStorytellingService()
    {
    	if (!$this->storytellingService) {
    		$this->storytellingService = $this->getServiceLocator()->get('playgroundflow_storytelling_service');
    	}
    
    	return $this->storytellingService;
    }
    
    public function setStorytellingService($storytellingService)
    {
    	$this->storytellingService = $storytellingService;
    
    	return $this;
    }
    
    public function getDomainService()
    {
        if (! $this->domainService) {
            $this->domainService = $this->getServiceLocator()->get('playgroundflow_domain_service');
        }
    
        return $this->domainService;
    }
    
    public function setDomainService(DomainService $domainService)
    {
        $this->domainService = $domainService;
    
        return $this;
    }
    
    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager ()
    {
        return $this->getServiceLocator();
    }
}
