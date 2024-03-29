<?php

namespace PlaygroundFlow\Controller\Admin;

use Laminas\Paginator\Paginator;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use PlaygroundFlow\Entity\OpenGraphWidget as Widget;
use Laminas\ServiceManager\ServiceLocatorInterface;

class WidgetController extends AbstractActionController
{
    /**
     * @var WidgetService
     */
    protected $adminWidgetService;

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
        $service    = $this->getAdminWidgetService();

        $widgets = $service->getWidgetMapper()->findAll();
        
        if (is_array($widgets)) {
            $paginator = new \Laminas\Paginator\Paginator(new \Laminas\Paginator\Adapter\ArrayAdapter($widgets));
            $paginator->setItemCountPerPage(25);
            $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
        } else {
            $paginator = $widgets;
        }

        return array(
            'widgets'   => $paginator,
        );
    }
    
    public function createAction()
    {
        $service    = $this->getAdminWidgetService();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/widget/widget');
    
        $widget = new Widget();
    
        $form = $this->getServiceLocator()->get('playgroundflow_widget_form');
        $form->bind($widget);
        $form->get('submit')->setLabel('Add');
        $form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/widget/create', array('widgetId' => 0)));
        $form->setAttribute('method', 'post');
    
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $widget = $service->create($data);
            if ($widget) {
                $this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The widget has been created');
    
                return $this->redirect()->toRoute('admin/playgroundflow/widget');
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
        $service    = $this->getAdminWidgetService();
        $widgetId = $this->getEvent()->getRouteMatch()->getParam('widgetId');
    
        if (!$widgetId) {
            return $this->redirect()->toRoute('admin/playgroundflow/widget/create');
        }
    
        $widget = $service->getWidgetMapper()->findById($widgetId);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/widget/widget');
    
        $form = $this->getServiceLocator()->get('playgroundflow_widget_form');
        $form->bind($widget);
        $form->get('submit')->setLabel('Add');
        $form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/widget/edit', array('widgetId' => $widgetId)));
        $form->setAttribute('method', 'post');
        $form->get('submit')->setLabel('Edit');
    
        if ($this->getRequest()->isPost()) {
            $data = array_merge(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $result = $service->edit($data, $widget);
    
            if ($result) {
                return $this->redirect()->toRoute('admin/playgroundflow/widget');
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
        $service    = $this->getAdminWidgetService();
        $widgetId = $this->getEvent()->getRouteMatch()->getParam('widgetId');
    
        if (!$widgetId) {
            return $this->redirect()->toRoute('admin/playgroundflow/widget/create');
        }
    
        $widget = $service->getWidgetMapper()->findById($widgetId);
        if ($widget) {
            try {
                $service->getWidgetMapper()->remove($widget);
                $this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The widget has been removed');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $this->flashMessenger()->setNamespace('playgroundflow')->addMessage('This widget is included in a storymapping and can\'t be removed');
                //throw $e;
            }
        }
    
        return $this->redirect()->toRoute('admin/playgroundflow/widget');
    }
    
    public function getAdminWidgetService()
    {
        if (!$this->adminWidgetService) {
            $this->adminWidgetService = $this->getServiceLocator()->get('playgroundflow_widget_service');
        }
    
        return $this->adminWidgetService;
    }
    
    public function setAdminWidgetService(AdminWidgetService $adminWidgetService)
    {
        $this->adminWidgetService = $adminWidgetService;
    
        return $this;
    }
}
