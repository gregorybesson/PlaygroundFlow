<?php

namespace PlaygroundFlow\Controller\Admin;

use Zend\Paginator\Paginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use PlaygroundFlow\Entity\OpenGraphAction as Action;
use Zend\ServiceManager\ServiceLocatorInterface;

class ActionController extends AbstractActionController
{
    /**
     * @var ActionService
     */
    protected $adminActionService;

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

    public function listAction()
    {
        $service    = $this->getAdminActionService();

        $actions = $service->getActionMapper()->findAll();
        
        if (is_array($actions)) {
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($actions));
            $paginator->setItemCountPerPage(25);
            $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
        } else {
            $paginator = $actions;
        }

        return array(
            'actions'   => $paginator,
        );
    }
    
    public function createAction()
    {
        $service    = $this->getAdminActionService();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/admin/action/action');
    
        $action = new Action();
    
        $form = $this->getServiceLocator()->get('playgroundflow_action_form');
        $form->bind($action);
        $form->get('submit')->setLabel('Add');
        $form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/action/create', array('actionId' => 0)));
        $form->setAttribute('method', 'post');
    
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $action = $service->create($data);
            if ($action) {
                $this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The action was created');
    
                return $this->redirect()->toRoute('admin/playgroundflow/action');
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
        $service    = $this->getAdminActionService();
        $actionId = $this->getEvent()->getRouteMatch()->getParam('actionId');
    
        if (!$actionId) {
            return $this->redirect()->toRoute('admin/playgroundflow/action/create');
        }
    
        $action = $service->getActionMapper()->findById($actionId);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/admin/action/action');
    
        $form = $this->getServiceLocator()->get('playgroundflow_action_form');
        $form->bind($action);
        $form->get('submit')->setLabel('Add');
        $form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/action/edit', array('actionId' => $actionId)));
        $form->setAttribute('method', 'post');
        $form->get('submit')->setLabel('Edit');
    
        if ($this->getRequest()->isPost()) {
            $data = array_merge(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $result = $service->edit($data, $action);
    
            if ($result) {
                return $this->redirect()->toRoute('admin/playgroundflow/action');
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
        $service    = $this->getAdminActionService();
        $actionId = $this->getEvent()->getRouteMatch()->getParam('actionId');
    
        if (!$actionId) {
            return $this->redirect()->toRoute('admin/playgroundflow/action/create');
        }
    
        $action = $service->getActionMapper()->findById($actionId);
        if ($action) {
            try {
                $service->getActionMapper()->remove($action);
                $this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The action has been removed');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $this->flashMessenger()->setNamespace('playgroundflow')->addMessage('This action is included in a story and can\'t be removed');
                //throw $e;
            }
        }
    
        return $this->redirect()->toRoute('admin/playgroundflow/action');
    }
    
    public function getAdminActionService()
    {
        if (!$this->adminActionService) {
            $this->adminActionService = $this->getServiceLocator()->get('playgroundflow_action_service');
        }
    
        return $this->adminActionService;
    }
    
    public function setAdminActionService(AdminActionService $adminActionService)
    {
        $this->adminActionService = $adminActionService;
    
        return $this;
    }
}
