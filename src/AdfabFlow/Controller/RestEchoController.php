<?php

namespace PlaygroundFlow\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
 
class RestEchoController extends AbstractRestfulController
{
    public function getList()
    {
        # code...
    }
 
    public function get($id)
    {
        # code...
    }
 
    /*
     * curl -i -H "Accept: application/json" -X POST -d "url=test&title=titre" http://127.0.0.1/playground/flow/XX-XX-YY-XX/rest/echo
     */
    public function create($data)
    {
        $response = $this->getResponse();
        $contentType = 'application/json';
        $adapter = '\Zend\Serializer\Adapter\Json';
        $response->getHeaders()->addHeaderLine('Content-Type',$contentType);
        $adapter = new $adapter;
        
        $appId = $this->getEvent()->getRouteMatch()->getParam('appId');
        
        $content = array(
            'result' => array(
                'message' => 'bravo',
                'success' => 'yeaaaaaaaaaaaah',
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
}
