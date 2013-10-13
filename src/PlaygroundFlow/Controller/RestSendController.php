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
     * curl -i -H "Accept: application/json" -X POST -d "url=test&title=titre" http://127.0.0.1/playground/flow/XX-XX-YY-XX/rest/echo
     */
    public function create($data)
    {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	$storyTellingService = $this->getStorytellingService();
    	
    	$data = $this->fromJson();
    	
    	$storyTelling = new \PlaygroundFlow\Entity\OpenGraphStoryTelling();
    	$storyTelling->setOpenGraphStoryMapping(null);
    	$storyTelling->setUser(null);
    	$storyTelling->setObject(json_encode($data['objects']));
    	$storyTelling->setPoints(99);
    	$storyTelling->setSecretKey(null);
    	$storyTellingService->getStoryTellingMapper()->insert($storyTelling);
    	
    	/*
    	$e->getTarget()->getEventManager()->trigger('story.'.$storyMapping->getId() , $this, array('storyTelling' => $storyTelling));
    	*/
    	
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
    	
    	$game = $service->getGameMapper()->findById(10);
    	

        
        $appId = $this->getEvent()->getRouteMatch()->getParam('appId');
        
        $data = array();
        if ($request->isPost()) {
        	$data = $this->fromJson();
        	$content = array(
        		'result' => array(
        			'message' => 'event recorded',
        			'success' => true,
       			),
        	);
        }else{
        	$content = array(
        		'result' => array(
        			'message' => 'No event detected',
        			'success' => false,
       			),
        	);
        }

        $response->setContent($adapter->serialize($content));
        
        // Add each parameters
        $args = array( 'apiKey' => $data["apiKey"], 'userId' => $data['user']['anonymous'] );
        $action = $data["action"];
        //$args["style"] = 'http://localhost/github/leaderboard/css/pmagento/all.css';
        $args["style"] = 'http://ic.adfab.fr/mouthnode/leaderboard/css/pmagento/all.css';
        $args["container"] = isset($data["container"]) ? $data["container"] : 'body';
        $url = "http://ic.adfab.fr:88/notification";

        $welcome ='<div id="welcome" class="playground" >' .
        		'<div >' .
        		'<a ' .
        		'href="#" ' .
        		'onclick="document.getElementById(\'welcome\').parentNode.removeChild(document.getElementById(\'welcome\'));" ' .
        		'>X</a>' .
        		'User ' . $data['user']['anonymous'] . ' has joined the game' .
        		'</div>' .
        		'</div>';
        
        $login ='<div id="welcome" class="playground" >' .
        		'<div >' .
        		'<a ' .
        		'href="#" ' .
        		'onclick="document.getElementById(\'welcome\').parentNode.removeChild(document.getElementById(\'welcome\'));" ' .
        		'>X</a>' .
        		'Welcome aboard ! Ready to hunt ?' . 
        		'</div>' .
        		'</div>';
        
        // html for other user that the one that just logged off
        $bye = '<div id="bye" class="playground" >' .
        		'<div >' .
        		'<a ' .
        		'href="#" ' .
        		'onclick="document.getElementById(\'bye\').parentNode.removeChild(document.getElementById(\'bye\'));" ' .
        		'>X</a>' .
        		'User ' . $data['user']['anonymous'] . ' has left the game' .
        		'</div>' .
        		'</div>';
        
        // html for user that found the treasure
        $win = '<div id="win" class="playground" >' .
        		'<div >' .
        		'<a ' .
        		'href="#" ' .
        		'onclick="document.getElementById(\'win\').parentNode.removeChild(document.getElementById(\'win\'));" ' .
        		'>X</a>' .
        		'Congratz ! You have found the treasure ! : ' .
        		'</div>' .
        		'</div>';
        
        // html for other user that loose and didn't find the treasure
        $loose = '<div id="loose" class="playground" >' .
        		'<div >' .
        		'<a ' .
        		'href="#" ' .
        		'onclick="document.getElementById(\'loose\').parentNode.removeChild(document.getElementById(\'loose\'));" ' .
        		'>X</a>' .
        		'User ' . $data['user']['anonymous'] . ' has found the secret treasure' .
        		'</div>' .
        		'</div>';
        
        $args["who"] = 'self';
        if($action=='find'){
        	$args["html"] = str_replace("=", "%3D", $win);
        } elseif($action=='login'){
        	$args["html"] = str_replace("=", "%3D", $login);
        }else{
        	$args["html"] = str_replace("=", "%3D", '');
        }
    	$this->sendRequest($url, $args);
    	
    	$args["who"] = 'others';
    	if($action=='find'){
    		$args["html"] = str_replace("=", "%3D", $loose);
    	} elseif($action=='login'){
    		$args["html"] = str_replace("=", "%3D", $welcome);
    	} else {
    		$args["html"] = str_replace("=", "%3D", $bye);
    	}		
    	$this->sendRequest($url, $args);
        
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
    
    /**
     * Actually send the notification
     *
     * @return void
     */
    public function sendRequest($url, $args)
    {
    
    	$ch = curl_init();
    	$curlConfig = array(
    			CURLOPT_URL            => $url,
    			CURLOPT_POST           => true,
    			CURLOPT_RETURNTRANSFER => true,
    			CURLOPT_POSTFIELDS     => json_encode($args)
    	);
    	// print the array that was sent
    	//echo "<pre>";
    	//var_dump($args);
    	curl_setopt_array($ch, $curlConfig);
    	$result = curl_exec($ch);
    	curl_close($ch);
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
}
