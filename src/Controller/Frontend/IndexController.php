<?php

namespace PlaygroundFlow\Controller\Frontend;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexController extends AbstractActionController
{
    /**
     *
     */
    protected $options;
    /**
     * @var gameService
     */
    protected $gameService;

    /**
     * @var quizService
     */
    protected $quizService;

    /**
     * @var pageService
     */
    protected $pageService;

    /**
     * @var rewardService
     */
    protected $rewardService;

    /**
     * @var achievementService
     */
    protected $achievementService;

     /**
     * @var mailService
     */
    protected $mailService;

    /**
     * @var storyTellingService
     */
    protected $storyTellingService;

    /**
     *
     * @var ServiceManager
     */
    protected $serviceLocator;

    public function __construct(ServiceLocatorInterface $locator)
    {
        $this->serviceLocator = $locator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * user sponsor friend
     */
    public function sponsorfriendsAction()
    {
        $user        = $this->zfcUserAuthentication()->getIdentity();
        $subjectMail = "Gagnez pleins de cadeaux sur Playground";
        $topic       = "Parrainage depuis l'espace client";
        $statusMail  = null;
        $sg          = $this->getGameService();
        
        $form = $this->getServiceLocator()->get('playgroundgame_sharemail_form');
        $form->setAttribute('method', 'post');

        $games = $this->getGameService()->getActiveGames();

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if ($form->isValid()) {
                $result = $this->getServiceLocator()->get('playgroundgame_lottery_service')->sendShareMail($data, $games, $user, 'share_game_list', $subjectMail, $topic);
                if ($result) {
                    $statusMail = true;
                }
            }
        }

        $secretKey = strtoupper(substr(sha1($user->getId().'####'.time()), 0, 15));
        $socialLinkUrl = $this->url()->fromRoute('frontend', array(), array('force_canonical' => true)).'?key='.$secretKey;
        // With core shortener helper
        $socialLinkUrl = $this->shortenUrl()->shortenUrl($socialLinkUrl);
        $fbShareImage = $this->url()->fromRoute('frontend', array(), array('force_canonical' => true)) . 'images/common/logo.png';

        $bitlyclient = $this->getOptions()->getBitlyUrl();
        $bitlyuser = $this->getOptions()->getBitlyUsername();
        $bitlykey = $this->getOptions()->getBitlyApiKey();

        $this->getViewHelper('HeadMeta')->setProperty('bt:client', $bitlyclient);
        $this->getViewHelper('HeadMeta')->setProperty('bt:user', $bitlyuser);
        $this->getViewHelper('HeadMeta')->setProperty('bt:key', $bitlykey);
        $this->getViewHelper('HeadMeta')->setProperty('og:image', $fbShareImage);
        
        $stories = $this->getStoryTellingService()->getStoryTellingMapper()->findWithStoryMappingByUser($user);

        $activities = array();

        foreach ($stories as $story) {
            $matchToFilter = false;
            foreach ($story->getOpenGraphStoryMapping()->getStory()->getObjects() as $object) {
                if ("sponsorize_a_friend" == strtolower($object->getCode())) {
                    $matchToFilter = true;
                }
            }
            if ($matchToFilter) {
                $activities[] = array("object" => json_decode($story->getObject(), true),
                                      "openGraphMapping" => $story->getOpenGraphStoryMapping()->getId(),
                                      "hint"   => $story->getOpenGraphStoryMapping()->getHint(),
                                      "picto" => $story->getOpenGraphStoryMapping()->getPicto(),
                                      "points" => $story->getPoints(),
                                      'created_at' => $story->getCreatedAt(),
                                      'definition' => $story->getOpenGraphStoryMapping()->getStory()->getDefinition(),
                                      'label' => $story->getOpenGraphStoryMapping()->getStory()->getLabel());
            }
        }

        $viewModel = new ViewModel(array(
            'activities'    => $activities,
            'statusMail'    => $statusMail,
            'form'          => $form,
            'socialLinkUrl' => $socialLinkUrl,
            'secretKey'         => $secretKey
        ));

        return $viewModel;
    }

    public function fbshareAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        $topic = "Partage Facebook depuis l'espace client";

        $fbId = $this->params()->fromQuery('fbId');
        $user = $this->zfcUserAuthentication()->getIdentity();

        if (!$fbId) {
            return false;
        }

        $this->getServiceLocator()->get('playgroundgame_lottery_service')->postFbWall($fbId, null, $user, $topic);

        return true;
    }

    public function tweetAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        $topic = "Partage Twitter depuis l'espace client";

        $tweetId = $this->params()->fromQuery('tweetId');
        $user = $this->zfcUserAuthentication()->getIdentity();

        if (!$tweetId) {
            return false;
        }

        $this->getServiceLocator()->get('playgroundgame_lottery_service')->postTwitter($tweetId, null, $user, $topic);

        return true;
    }

    public function googleAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        $topic = "Partage Google depuis l'espace client";

        $googleId = $this->params()->fromQuery('googleId');
        $user = $this->zfcUserAuthentication()->getIdentity();

        if (!$googleId) {
            return false;
        }

        $this->getServiceLocator()->get('playgroundgame_lottery_service')->postGoogle($googleId, null, $user, $topic);

        return true;
    }

    public function getGameService()
    {
        if (!$this->gameService) {
            $this->gameService = $this->getServiceLocator()->get('playgroundgame_game_service');
        }

        return $this->gameService;
    }

    public function setGameService(GameService $gameService)
    {
        $this->gameService = $gameService;

        return $this;
    }

    public function getOptions()
    {
        if (!$this->options) {
            $this->setOptions($this->getServiceLocator()->get('playgroundcore_module_options'));
        }

        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    protected function getViewHelper($helperName)
    {
        return $this->getServiceLocator()->get('ViewHelperManager')->get($helperName);
    }

     /**
      * retrieve storyTelling service
      *
      * @return Service/storyTelling $storyTellingService
      */
    public function getStoryTellingService()
    {
        if (!$this->storyTellingService) {
            $this->storyTellingService = $this->getServiceLocator()->get('playgroundflow_storytelling_service');
        }

        return $this->storyTellingService;
    }
}
