<?php

namespace PlaygroundFlow\Controller\Admin;

use Zend\Paginator\Paginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use PlaygroundFlow\Entity\OpenGraphStory as Story;

class StoryController extends AbstractActionController
{
    /**
     * @var StoryService
     */
    protected $adminStoryService;
    
    public function listAction()
    {
        $service    = $this->getAdminStoryService();

        $stories = $service->getStoryMapper()->findAll();
        
        if (is_array($stories)) {
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($stories));
            $paginator->setItemCountPerPage(25);
            $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
        } else {
            $paginator = $stories;
        }

        return array(
            'stories'   => $paginator,
        );
    }
    
    public function createAction()
    {
        $service    = $this->getAdminStoryService();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/admin/story/story');
    
        $story = new Story();
    
        $form = $this->getServiceLocator()->get('playgroundflow_story_form');
        $form->bind($story);
        $form->get('submit')->setLabel('Add');
        $form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/story/create', array('storyId' => 0)));
        $form->setAttribute('method', 'post');
    
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $story = $service->create($data);
            if ($story) {
                $this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The story was created');
    
                return $this->redirect()->toRoute('admin/playgroundflow/story');
            }
        }
    
        return $viewModel->setVariables(
            array(
                'form' => $form,
            )
        );
    }
    
    public function editAction()
    {
        $service    = $this->getAdminStoryService();
        $storyId = $this->getEvent()->getRouteMatch()->getParam('storyId');
    
        if (!$storyId) {
            return $this->redirect()->toRoute('admin/playgroundflow/story/create');
        }
    
        $story = $service->getStoryMapper()->findById($storyId);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/admin/story/story');
    
        $form = $this->getServiceLocator()->get('playgroundflow_story_form');
        $form->bind($story);
        $form->get('submit')->setLabel('Add');
        $form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/story/edit', array('storyId' => $storyId)));
        $form->setAttribute('method', 'post');
        $form->get('submit')->setLabel('Edit');
    
        if ($this->getRequest()->isPost()) {
            $data = array_merge(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $result = $service->edit($data, $story);
    
            if ($result) {
                return $this->redirect()->toRoute('admin/playgroundflow/story');
            }
        }
    
        return $viewModel->setVariables(
            array(
                'form' => $form,
            )
        );
    }
    
    public function removeAction()
    {
        $service    = $this->getAdminStoryService();
        $storyId = $this->getEvent()->getRouteMatch()->getParam('storyId');
    
        if (!$storyId) {
            return $this->redirect()->toRoute('admin/playgroundflow/story/create');
        }
    
        $story = $service->getStoryMapper()->findById($storyId);
        if ($story) {
            try {
                $service->getStoryMapper()->remove($story);
                $this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The story has been removed');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $this->flashMessenger()->setNamespace('playgroundflow')->addMessage('This story is included in a story and can\'t be removed');
                //throw $e;
            }
        }
    
        return $this->redirect()->toRoute('admin/playgroundflow/story');
    }
    
    public function getAdminStoryService()
    {
        if (!$this->adminStoryService) {
            $this->adminStoryService = $this->getServiceLocator()->get('playgroundflow_story_service');
        }
    
        return $this->adminStoryService;
    }
    
    public function setAdminStoryService(AdminStoryService $adminStoryService)
    {
        $this->adminStoryService = $adminStoryService;
    
        return $this;
    }
}
