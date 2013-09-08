<?php

namespace PlaygroundFlow\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     *
     */
    protected $options;
    
    public function initAction()
    {
        $appId = $this->getEvent()->getRouteMatch()->getParam('appId');

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        return $viewModel->setVariables(array('appId' => $appId));
    }
}
