<?php

namespace PlaygroundFlow\Controller;

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

    public function initAction()
    {
        $appId = $this->getEvent()->getRouteMatch()->getParam('appId');

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        return $viewModel->setVariables(array('appId' => $appId));
    }
}
