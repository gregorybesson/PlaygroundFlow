<?php

namespace PlaygroundFlow\Controller\Admin;

use Zend\Paginator\Paginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use PlaygroundFlow\Entity\OpenGraphStoryMapping as Mapping;
use PlaygroundFlow\Entity\OpenGraphWebTechno as WebTechno;
use PlaygroundFlow\Entity\OpenGraphObjectMapping as ObjectMapping;

class WebTechnoController extends AbstractActionController
{
	/**
	 * @var WebTechnoService
	 */
	protected $adminWebTechnoService;
	
	public function listAction()
	{
		$service = $this->getAdminWebTechnoService();
        
        $webtechnos = $service->getWebTechnoMapper()->findAll();
        
        if (is_array($webtechnos)) {
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($webtechnos));
            $paginator->setItemCountPerPage(25);
            $paginator->setCurrentPageNumber($this->getEvent()
                ->getRouteMatch()
                ->getParam('p'));
        } else {
            $paginator = $webtechnos;
        }
        
        return array(
            'webtechnos' => $paginator
        );
	}

	public function createAction()
    {
        $service = $this->getAdminWebTechnoService();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/web-techno/webtechno');
        
        $webtechno = new WebTechno();
        
        $form = $this->getServiceLocator()->get('playgroundflow_webtechno_form');
        $form->bind($webtechno);
        $form->get('submit')->setLabel('Add');
        $form->setAttribute('action', $this->url()
            ->fromRoute('admin/playgroundflow/webtechno/create', array(
            'webTechnoId' => 0
        )));
        $form->setAttribute('method', 'post');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge($this->getRequest()
                ->getPost()
                ->toArray(), $this->getRequest()
                ->getFiles()
                ->toArray());
            $webtechno = $service->create($data);
            if ($webtechno) {
                $this->flashMessenger()
                    ->setNamespace('playgroundflow')
                    ->addMessage('The webtechno was created');
                
                return $this->redirect()->toRoute('admin/playgroundflow/webtechno');
            }
        }
        
        return $viewModel->setVariables(array(
            'form' => $form
        ));
    }

    public function editAction()
    {
        $service = $this->getAdminWebTechnoService();
        $webTechnoId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('webTechnoId');
        
        if (! $webTechnoId) {
            return $this->redirect()->toRoute('admin/playgroundflow/webtechno/create');
        }
        
        $webTechno = $service->getWebTechnoMapper()->findById($webTechnoId);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/web-techno/webtechno');
        
        $form = $this->getServiceLocator()->get('playgroundflow_webtechno_form');
        $form->bind($webTechno);
        $form->setAttribute('action', $this->url()
            ->fromRoute('admin/playgroundflow/webtechno/edit', array(
            'webTechnoId' => $webTechnoId
        )));
        $form->setAttribute('method', 'post');
        $form->get('submit')->setLabel('Edit');
        
        if ($this->getRequest()->isPost()) {
            $data = array_merge($this->getRequest()
                ->getPost()
                ->toArray(), $this->getRequest()
                ->getFiles()
                ->toArray());
            $result = $service->edit($data, $webTechno);
            
            if ($result) {
                return $this->redirect()->toRoute('admin/playgroundflow/webtechno');
            }
        }
        
        return $viewModel->setVariables(array(
            'form' => $form
        ));
    }

    public function removeAction()
    {
        $service = $this->getAdminWebTechnoService();
        $webTechnoId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('webTechnoId');
        
        if (! $webTechnoId) {
            return $this->redirect()->toRoute('admin/playgroundflow/webtechno/create');
        }
        
        $webTechno = $service->getWebTechnoMapper()->findById($webTechnoId);
        if ($webTechno) {
            try {
                $service->getWebTechnoMapper()->remove($webTechno);
                $this->flashMessenger()
                    ->setNamespace('playgroundflow')
                    ->addMessage('The webTechno has been removed');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $this->flashMessenger()
                    ->setNamespace('playgroundflow')
                    ->addMessage('This webTechno is can\'t be removed');
                // throw $e;
            }
        }
        
        return $this->redirect()->toRoute('admin/playgroundflow/webtechno');
    }

    public function listStoryAction()
    {
        $webTechnoId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('webTechnoId');
        if (! $webTechnoId) {
            return $this->redirect()->toRoute('admin/playgroundflow/webtechno');
        }
        $service = $this->getAdminWebTechnoService();
        
        $webTechno = $service->getWebTechnoMapper()->findById($webTechnoId);
        $mapping = $service->getStoryMappingMapper()->findByWebTechnoId($webTechnoId);
        
        if (is_array($mapping)) {
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($mapping));
            $paginator->setItemCountPerPage(25);
            $paginator->setCurrentPageNumber($this->getEvent()
                ->getRouteMatch()
                ->getParam('p'));
        } else {
            $paginator = $mapping;
        }
        
        return array(
            'mapping' => $paginator,
            'webTechnoId' => $webTechnoId,
            'webTechno' => $webTechno
        );
    }

    public function createStoryAction()
    {
        $service = $this->getAdminWebTechnoService();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/web-techno/story');
        $webTechnoId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('webTechnoId');
        
        if (! $webTechnoId) {
            return $this->redirect()->toRoute('admin/playgroundflow/webtechno');
        }
        
        $mapping = new Mapping();
        
        $form = $this->getServiceLocator()->get('playgroundflow_storymapping_form');
        $form->bind($mapping);
        $form->get('submit')->setLabel('Add');
        $form->get('webTechnoId')->setAttribute('value', $webTechnoId);
        $form->setAttribute('action', $this->url()
            ->fromRoute('admin/playgroundflow/webtechno/story/create', array(
            'webTechnoId' => $webTechnoId,
            'mappingId' => 0
        )));
        $form->setAttribute('method', 'post');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge($this->getRequest()
                ->getPost()
                ->toArray(), $this->getRequest()
                ->getFiles()
                ->toArray());
            $storyMapping = $service->createStory($data);
            if ($storyMapping) {
                $this->flashMessenger()
                    ->setNamespace('playgroundflow')
                    ->addMessage('The story was created');
                
                return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story', array(
                    'webTechnoId' => $webTechnoId
                ));
            }
        }
        
        return $viewModel->setVariables(array(
            'form' => $form
        ));
    }

    public function editStoryAction()
    {
        $service = $this->getAdminWebTechnoService();
        $webTechnoId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('webTechnoId');
        $mappingId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('mappingId');
        
        if (! $mappingId) {
            return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story', array(
                'webTechnoId' => $webTechnoId
            ));
        }
        
        $storyMapping = $service->getStoryMappingMapper()->findById($mappingId);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/web-techno/story');
        
        $form = $this->getServiceLocator()->get('playgroundflow_storymapping_form');
        $form->bind($storyMapping);
        $form->get('webTechnoId')->setAttribute('value', $webTechnoId);
        $form->setAttribute('action', $this->url()
            ->fromRoute('admin/playgroundflow/webtechno/story/edit', array(
            'webTechnoId' => $webTechnoId,
            'mappingId' => $mappingId
        )));
        $form->setAttribute('method', 'post');
        $form->get('submit')->setLabel('Edit');
        
        if ($this->getRequest()->isPost()) {
            $data = array_merge($this->getRequest()
                ->getPost()
                ->toArray(), $this->getRequest()
                ->getFiles()
                ->toArray());
            
            $result = $service->editStory($data, $storyMapping);
            
            if ($result) {
                return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story', array(
                    'webTechnoId' => $webTechnoId
                ));
            }
        }
        
        return $viewModel->setVariables(array(
            'form' => $form
        ));
    }

    public function removeStoryAction()
    {
        $service = $this->getAdminWebTechnoService();
        $webTechnoId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('webTechnoId');
        $mappingId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('mappingId');
        
        if (! $mappingId) {
            return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story', array(
                'webTechnoId' => $webTechnoId
            ));
        }
        
        $storyMapping = $service->getStoryMappingMapper()->findById($mappingId);

        if ($storyMapping) {
            try {
                $service->getStoryMappingMapper()->remove($storyMapping);
                $this->flashMessenger()
                    ->setNamespace('playgroundflow')
                    ->addMessage('The story has been removed');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $this->flashMessenger()
                    ->setNamespace('playgroundflow')
                    ->addMessage('This story is included in a story and can\'t be removed');
                // throw $e;
            }
        }
        
        return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story', array(
            'webTechnoId' => $webTechnoId
        ));
    }

    public function listObjectAction()
    {
        $service = $this->getAdminWebTechnoService();
        $webTechnoId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('webTechnoId');
        $mappingId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('mappingId');
        if (! $mappingId) {
            return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story', array(
                'webTechnoId' => $webTechnoId
            ));
        }
        $storyMapping = $service->getStoryMappingMapper()->findById($mappingId);
        
        $objects = $storyMapping->getObjects()->toArray();
        
        if (is_array($objects)) {
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($objects));
            $paginator->setItemCountPerPage(25);
            $paginator->setCurrentPageNumber($this->getEvent()
                ->getRouteMatch()
                ->getParam('p'));
        } else {
            $paginator = $objects;
        }
        
        return array(
            'mapping' => $paginator,
            'mappingId' => $mappingId,
            'webTechnoId' => $webTechnoId,
            'objects' => $paginator
        );
    }

    public function createObjectAction()
    {
        $service = $this->getAdminWebTechnoService();
        $webTechnoId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('webTechnoId');
        $mappingId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('mappingId');
        $objectId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('objectId');
        if (! $mappingId) {
            return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story', array(
                'webTechnoId' => $webTechnoId
            ));
        }
        $storyMapping = $service->getStoryMappingMapper()->findById($mappingId);
        $objectMapping = new ObjectMapping();
        $objectMapping->setStoryMapping($storyMapping);
        
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/web-techno/object');
        
        $objectsArray = array();
        foreach ($storyMapping->getStory()->getObjects() as $object) {
            $objectsArray[$object->getId()] = $object->getLabel();
        }
        
        $form = $this->getServiceLocator()->get('playgroundflow_objectmapping_form');
        $form->get('object')->setAttribute('options', $objectsArray);

        $form->bind($objectMapping);
        $form->setAttribute('action', $this->url()
            ->fromRoute('admin/playgroundflow/webtechno/story/object/create', array(
            'webTechnoId' => $webTechnoId,
            'mappingId' => $mappingId,
            'objectId' => 0
        )));
        $form->setAttribute('method', 'post');
        $form->get('submit')->setLabel('Edit');
        
        if ($this->getRequest()->isPost()) {
            $data = array_merge($this->getRequest()
                ->getPost()
                ->toArray(), $this->getRequest()
                ->getFiles()
                ->toArray());
            $result = $service->createObject($data, $objectMapping);
            
            if ($result) {
                return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story/object', array(
                    'webTechnoId' => $webTechnoId,
                    'mappingId' => $mappingId
                ));
            }
        }
        
        return $viewModel->setVariables(array(
            'form' => $form,
            'webTechnoId' => $webTechnoId,
            'mappingId' => $mappingId
        ));
    }

    public function editObjectAction()
    {
        $service = $this->getAdminWebTechnoService();
        $webTechnoId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('webTechnoId');
        $mappingId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('mappingId');
        $objectId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('objectId');
        if (! $mappingId) {
            return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story', array(
                'webTechnoId' => $webTechnoId
            ));
        }
        $storyMapping = $service->getStoryMappingMapper()->findById($mappingId);
        $objectMapping = $service->getObjectMappingMapper()->findById($objectId);
        
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/web-techno/object');
        
        $objectsArray = array();  
        foreach ($storyMapping->getStory()->getObjects() as $object) {
            $objectsArray[$object->getId()] = $object->getLabel();
        }
        
        $form = $this->getServiceLocator()->get('playgroundflow_objectmapping_form');
        $form->get('object')->setAttribute('options', $objectsArray);
        $form->bind($objectMapping);
        $form->setAttribute('action', $this->url()
            ->fromRoute('admin/playgroundflow/webtechno/story/object/edit', array(
            'webTechnoId' => $webTechnoId,
            'mappingId' => $mappingId,
            'objectId' => $objectMapping->getId()
        )));
        $form->setAttribute('method', 'post');
        $form->get('submit')->setLabel('Edit');
        
        if ($this->getRequest()->isPost()) {
            $data = array_merge($this->getRequest()
                ->getPost()
                ->toArray(), $this->getRequest()
                ->getFiles()
                ->toArray());
            $result = $service->editObject($data, $objectMapping);
            
            if ($result) {
                return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story/object', array(
                    'webTechnoId' => $webTechnoId,
                    'mappingId' => $mappingId
                ));
            }
        }
        
        return $viewModel->setVariables(array(
            'form' => $form,
            'webTechnoId' => $webTechnoId,
            'mappingId' => $mappingId
        ));
    }

    public function removeObjectAction()
    {
        $service = $this->getAdminWebTechnoService();
        $webTechnoId = $this->getEvent()
        ->getRouteMatch()
        ->getParam('webTechnoId');
        $mappingId = $this->getEvent()
        ->getRouteMatch()
        ->getParam('mappingId');
        $objectId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('objectId');
        
        if (! $objectId) {
            return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story/object', array(
                'webTechnoId' => $webTechnoId,
                'mappingId' => $mappingId,
                'objectId' => $objectId
            ));
        }
        
        $object = $service->getObjectMappingMapper()->findById($objectId);
        if ($object) {
            try {
                $service->getObjectMappingMapper()->remove($object);
                $this->flashMessenger()
                    ->setNamespace('playgroundflow')
                    ->addMessage('The object has been removed');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $this->flashMessenger()
                    ->setNamespace('playgroundflow')
                    ->addMessage('This object is included in a story and can\'t be removed');
                // throw $e;
            }
        }
        
        return $this->redirect()->toRoute('admin/playgroundflow/webtechno/story/object', array(
            'webTechnoId' => $webTechnoId,
            'mappingId' => $mappingId,
        ));
    }

	public function getAdminWebTechnoService()
    {
        if (! $this->adminWebTechnoService) {
            $this->adminWebTechnoService = $this->getServiceLocator()->get('playgroundflow_webtechno_service');
        }
        
        return $this->adminWebTechnoService;
    }

}
	