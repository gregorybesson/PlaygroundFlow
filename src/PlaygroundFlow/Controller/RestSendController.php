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
    /**
     * @var prospectService
     */
    protected $prospectService;
    /**
     * @var domainService
     */
    protected $domainService;
    /**
     * @var userService
     */
    protected $userService;
    /**
     * @var userDomainService
     */
    protected $userDomainService;

    /**
     * @var leaderboardService
     */
    protected $leaderboardService;



    public function getList()
    {
        $response = $this->getResponse();
        
        $contentType = 'application/json';
        $adapter = '\Zend\Serializer\Adapter\Json';
        $response->getHeaders()->addHeaderLine('Content-Type', $contentType);
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
        $user = null;
        
        $data = $this->fromJson();
        $storyMappingId = $data['story_mapping_id'];
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

        $domain = $this->getDomainService()->getDomain($this);

        $storyTelling = new \PlaygroundFlow\Entity\OpenGraphStoryTelling();
        
        $storyTelling->setObject(json_encode($data['objects']))
            ->setPoints($storyMapping->getPoints())
            ->setSecretKey(null);

        if (!empty($data['user']['email'])) {
            // J'ai un user qui est identifié : user playground
            $user = $this->getUserService()->findUserOrCreateByEmail($data['user']['email']);
        
            // Association prospect - user si c'etait un prospect avant
            $prospect = $this->getProspectService()->getProspectMapper()->findOneBy(array('prospect' => $data['user']['anonymous']));
            if (!empty($prospect)) {
                if ($prospect->getUser() == null) {
                    $prospect->setUser($user);
                    $prospect = $this->getProspectService()->getProspectMapper()->update($prospect);
                }
                $storyTelling->setProspect($prospect);
            }
            $userDomain = $this->getUserDomainService()->findUserDomainOrCreateByUserAndDomain($user, $domain);
            // Association story telling à un utilisateur
            $storyTelling->setUser($user);
        
        } elseif (!empty($data['user']['anonymous'])) {
            // J'ai un anonymous
            $prospect = $this->getProspectService()->findProspectOrCreateByProspectAndDomain($data['user']['anonymous'], $domain);
            // Association story telling à un prospect
            $storyTelling->setProspect($prospect);
        }
        
        $storyTelling->setOpenGraphStoryMapping($storyMapping);
        // Creation de la storyTelling
        $storyTellingService->getStoryTellingMapper()->insert($storyTelling);

        // Si il y a un user, on met a jour son classement
        if (!empty($user)) {
            $this->getLeaderboardService()->addPoints($storyMapping, $user);
        }

        $storyTellingService->tellStory($storyTelling);
        
        $this->getEventManager()->trigger('story.'.$storyMapping->getId(), $this, array('storyTelling' => $storyTelling));
        
        $contentType = 'application/json';
        $adapter = '\Zend\Serializer\Adapter\Json';
        $response->getHeaders()->addHeaderLine('Content-Type', $contentType);
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
    
    public function fromJson()
    {
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
    
    /**
     * Retrieve service domain instance
     *
     * @return Service/Domain domainService
     */
    public function getDomainService()
    {
        if (! $this->domainService) {
            $this->domainService = $this->getServiceLocator()->get('playgroundflow_domain_service');
        }
    
        return $this->domainService;
    }

    /**
     * Retrieve service prospect instance
     *
     * @return Service/Prospect prospectService
     */
    public function getProspectService()
    {
        if (! $this->prospectService) {
            $this->prospectService = $this->getServiceLocator()->get('playgroundflow_prospect_service');
        }
    
        return $this->prospectService;
    }

    /**
     * Retrieve service user instance
     *
     * @return Service/User userService
     */
    public function getUserService()
    {
        if (! $this->userService) {
            $this->userService = $this->getServiceLocator()->get('playgrounduser_user_service');
        }
    
        return $this->userService;
    }

    /**
     * Retrieve service userdomain instance
     *
     * @return Service/UserDomain userDomainService
     */
    public function getUserDomainService()
    {
        if (! $this->userDomainService) {
            $this->userDomainService = $this->getServiceLocator()->get('playgroundflow_user_domain_service');
        }
    
        return $this->userDomainService;
    }

    /**
     * Retrieve service leaderboardservice instance
     *
     * @return Service/Leaderboard leaderboardService
     */
    public function getLeaderboardService()
    {

        if (! $this->leaderboardService) {
            $this->leaderboardService = $this->getServiceLocator()->get('playgroundreward_leaderboard_service');
        }
    
        return $this->leaderboardService;
    }
    
    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->getServiceLocator();
    }
}
