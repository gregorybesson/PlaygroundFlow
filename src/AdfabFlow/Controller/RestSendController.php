<?php

namespace PlaygroundFlow\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
 
class RestSendController extends AbstractRestfulController
{
	/**
	 * @var GameService
	 */
	protected $adminGameService;
	
    public function getList()
    {
        return;
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
    	$service = $this->getAdminGameService();
    	$game = $service->getGameMapper()->findById(10);
    	
        $contentType = 'application/json';
        $adapter = '\Zend\Serializer\Adapter\Json';
        $response->getHeaders()->addHeaderLine('Content-Type',$contentType);
        $adapter = new $adapter;
        
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
    
    public function getAdminGameService()
    {
    	if (!$this->adminGameService) {
    		$this->adminGameService = $this->getServiceLocator()->get('adfabgame_treasurehunt_service');
    	}
    
    	return $this->adminGameService;
    }
    
    public function setAdminGameService(AdminGameService $adminGameService)
    {
    	$this->adminGameService = $adminGameService;
    
    	return $this;
    }
}
