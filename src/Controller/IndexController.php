<?php

namespace PlaygroundFlow\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\ServiceManager\ServiceLocatorInterface;

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
