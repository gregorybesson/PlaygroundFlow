<?php

namespace PlaygroundFlow\Controller\Admin;

use Zend\Paginator\Paginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use PlaygroundFlow\Entity\OpenGraphDomain as Domain;
use PlaygroundFlow\Entity\OpenGraphStoryMapping as Mapping;

class DomainController extends AbstractActionController
{
	/**
	 * @var DomainService
	 */
	protected $adminDomainService;
	
	public function listAction()
	{
	
		$service 	= $this->getAdminDomainService();

		$domains = $service->getDomainMapper()->findAll();
		
		if (is_array($domains)) {
			$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($domains));
			$paginator->setItemCountPerPage(25);
			$paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
		} else {
			$paginator = $domains;
		}

		return array(
			'domains' 	=> $paginator,
		);
	}
	
	public function createAction()
	{
		$service 	= $this->getAdminDomainService();
		$viewModel = new ViewModel();
		$viewModel->setTemplate('playground-flow/admin/domain/domain');
	
		$domain = new Domain();
	
		$form = $this->getServiceLocator()->get('playgroundflow_domain_form');
		$form->bind($domain);
		$form->get('submit')->setLabel('Add');
		$form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/domain/create', array('domainId' => 0)));
		$form->setAttribute('method', 'post');
	
		$request = $this->getRequest();
		if ($request->isPost()) {
			$data = array_merge(
				$this->getRequest()->getPost()->toArray(),
				$this->getRequest()->getFiles()->toArray()
			);
			$domain = $service->create($data);
			if ($domain) {
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The domain was created');
	
				return $this->redirect()->toRoute('admin/playgroundflow/domain');
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
		$service 	= $this->getAdminDomainService();
		$domainId = $this->getEvent()->getRouteMatch()->getParam('domainId');
	
		if (!$domainId) {
			return $this->redirect()->toRoute('admin/playgroundflow/domain/create');
		}
	
		$domain = $service->getDomainMapper()->findById($domainId);
		$viewModel = new ViewModel();
		$viewModel->setTemplate('playground-flow/admin/domain/domain');
	
		$form = $this->getServiceLocator()->get('playgroundflow_domain_form');
		$form->bind($domain);
		$form->get('submit')->setLabel('Add');
		$form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/domain/edit', array('domainId' => $domainId)));
		$form->setAttribute('method', 'post');
		$form->get('submit')->setLabel('Edit');
	
		if ($this->getRequest()->isPost()) {
			$data = array_merge(
					$this->getRequest()->getPost()->toArray(),
					$this->getRequest()->getFiles()->toArray()
			);
			$result = $service->edit($data, $domain);
	
			if ($result) {
				return $this->redirect()->toRoute('admin/playgroundflow/domain');
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
		$service 	= $this->getAdminDomainService();
		$domainId = $this->getEvent()->getRouteMatch()->getParam('domainId');
	
		if (!$domainId) {
			return $this->redirect()->toRoute('admin/playgroundflow/domain/create');
		}
	
		$domain = $service->getDomainMapper()->findById($domainId);
		if ($domain) {
			try {
				$service->getDomainMapper()->remove($domain);
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The domain has been removed');
			} catch (\Doctrine\DBAL\DBALException $e) {
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('This domain is included in a domain and can\'t be removed');
				//throw $e;
			}
		}
	
		return $this->redirect()->toRoute('admin/playgroundflow/domain');
	}
	

	public function listStoryAction()
	{
		$domainId = $this->getEvent()->getRouteMatch()->getParam('domainId');
		if (!$domainId) {
			return $this->redirect()->toRoute('admin/playgroundflow/domain');
		}
		$service 	= $this->getAdminDomainService();
	
		$domain = $service->getDomainMapper()->findById($domainId);
		$mapping = $service->getStoryMappingMapper()->findByDomainId($domainId);
	
		if (is_array($mapping)) {
			$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($mapping));
			$paginator->setItemCountPerPage(25);
			$paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
		} else {
			$paginator = $mapping;
		}
	
		return array(
				'mapping' 	=> $paginator,
				'domainId' 	=> $domainId,
				'domain' 	=> $domain,
		);
	}
	
	public function createStoryAction()
	{
		$service 	= $this->getAdminDomainService();
		$viewModel = new ViewModel();
		$viewModel->setTemplate('playground-flow/admin/domain/story');
		$domainId = $this->getEvent()->getRouteMatch()->getParam('domainId');
	
		if (!$domainId) {
			return $this->redirect()->toRoute('admin/playgroundflow/domain');
		}
	
		$mapping = new Mapping();
	
		$form = $this->getServiceLocator()->get('playgroundflow_storymapping_form');
		$form->bind($mapping);
		$form->get('submit')->setLabel('Add');
		$form->get('domainId')->setAttribute('value', $domainId);
		$form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/domain/story/create', array('domainId' => $domainId, 'mappingId' => 0)));
		$form->setAttribute('method', 'post');
	
		$request = $this->getRequest();
		if ($request->isPost()) {
			$data = array_merge(
					$this->getRequest()->getPost()->toArray(),
					$this->getRequest()->getFiles()->toArray()
			);
			$storyMapping = $service->createStory($data);
			if ($storyMapping) {
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The story was created');
	
				return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array('domainId' => $domainId));
			}
		}
	
		return $viewModel->setVariables(
			array(
				'form' => $form,
			)
		);
	}
	
	public function editStoryAction()
	{
		$service 	= $this->getAdminDomainService();
		$domainId = $this->getEvent()->getRouteMatch()->getParam('domainId');
		$mappingId = $this->getEvent()->getRouteMatch()->getParam('mappingId');
	
		if (!$mappingId) {
			return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array('domainId' => $domainId));
		}
	
		$storyMapping = $service->getStoryMappingMapper()->findById($mappingId);
		$viewModel = new ViewModel();
		$viewModel->setTemplate('playground-flow/admin/domain/story');
	
		$form = $this->getServiceLocator()->get('playgroundflow_storymapping_form');
		$form->bind($storyMapping);
		$form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/domain/story/edit', array('domainId' => $domainId, 'mappingId' => $mappingId)));
		$form->setAttribute('method', 'post');
		$form->get('submit')->setLabel('Edit');
	
		if ($this->getRequest()->isPost()) {
			$data = array_merge(
					$this->getRequest()->getPost()->toArray(),
					$this->getRequest()->getFiles()->toArray()
			);
			$result = $service->editStory($data, $storyMapping);
	
			if ($result) {
				return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array('domainId' => $domainId));
			}
		}
	
		return $viewModel->setVariables(
			array(
				'form' => $form,
			)
		);
	}
	
	public function removeStoryAction()
	{
		$service 	= $this->getAdminDomainService();
		$domainId = $this->getEvent()->getRouteMatch()->getParam('domainId');
		$mappingId = $this->getEvent()->getRouteMatch()->getParam('mappingId');
	
		if (!$mappingId) {
			return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array('domainId' => $domainId));
		}
	
		$storyMapping = $service->getStoryMappingMapper()->findById($mappingId);
		if ($storyMapping) {
			try {
				$service->getStoryMappingMapper()->remove($storyMapping);
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The story has been removed');
			} catch (\Doctrine\DBAL\DBALException $e) {
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('This story is included in a story and can\'t be removed');
				//throw $e;
			}
		}
	
		return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array('domainId' => $domainId));
	}
	
	public function listAttributeAction()
	{
		$service 	= $this->getAdminDomainService();
		$domainId = $this->getEvent()->getRouteMatch()->getParam('domainId');
		$mappingId = $this->getEvent()->getRouteMatch()->getParam('mappingId');
		if (!$mappingId) {
			return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array('domainId' => $domainId));
		}
		$storyMapping = $service->getStoryMappingMapper()->findById($mappingId);
	
		$attributes = $storyMapping->getAttributes()->toArray();
	
		if (is_array($attributes)) {
			$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($attributes));
			$paginator->setItemCountPerPage(25);
			$paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
		} else {
			$paginator = $attributes;
		}
	
		return array(
			'mapping' 	=> $paginator,
			'mappingId' => $mappingId,
			'domainId' 	=> $domainId,
			'attributes' 	=> $paginator,
		);
	}
	
	/*public function createAttributeAction()
	{
		$service 	= $this->getAdminObjectService();
		$viewModel = new ViewModel();
		$viewModel->setTemplate('playground-flow/admin/object/attribute');
		$objectId = $this->getEvent()->getRouteMatch()->getParam('objectId');
	
		if (!$objectId) {
			return $this->redirect()->toRoute('admin/playgroundflow/object');
		}
	
		$attribute = new Attribute();
	
		$form = $this->getServiceLocator()->get('playgroundflow_objectattribute_form');
		$form->bind($attribute);
		$form->get('submit')->setLabel('Add');
		$form->get('objectId')->setAttribute('value', $objectId);
		$form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/object/attribute/create', array('objectId' => $objectId, 'attributeId' => 0)));
		$form->setAttribute('method', 'post');
	
		$request = $this->getRequest();
		if ($request->isPost()) {
			$data = array_merge(
					$this->getRequest()->getPost()->toArray(),
					$this->getRequest()->getFiles()->toArray()
			);
			$attribute = $service->createAttribute($data);
			if ($attribute) {
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The attribute was created');
	
				return $this->redirect()->toRoute('admin/playgroundflow/object/attribute', array('objectId' => $objectId));
			}
		}
	
		return $viewModel->setVariables(
				array(
						'form' => $form,
				)
		);
	}*/
	
	public function editAttributeAction()
	{
		$service 	= $this->getAdminDomainService();
		$domainId = $this->getEvent()->getRouteMatch()->getParam('domainId');
		$mappingId = $this->getEvent()->getRouteMatch()->getParam('mappingId');
		$attributeId = $this->getEvent()->getRouteMatch()->getParam('attributeId');
		if (!$attributeId) {
			return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array('domainId' => $domainId));
		}
		$attributeMapping = $service->getObjectAttributeMappingMapper()->findById($attributeId);
		
		$viewModel = new ViewModel();
		$viewModel->setTemplate('playground-flow/admin/domain/attribute');
	
		$form = $this->getServiceLocator()->get('playgroundflow_objectattributemapping_form');
		$form->bind($attributeMapping);
		$form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/domain/story/attribute/edit', array('domainId' => $domainId, 'mappingId' => $mappingId, 'attributeId' => $attributeMapping->getId())));
		$form->setAttribute('method', 'post');
		$form->get('submit')->setLabel('Edit');
	
		if ($this->getRequest()->isPost()) {
			$data = array_merge(
				$this->getRequest()->getPost()->toArray(),
				$this->getRequest()->getFiles()->toArray()
			);
			$result = $service->editAttribute($data, $attributeMapping);
	
			if ($result) {
				return $this->redirect()->toRoute('admin/playgroundflow/domain/story/attribute', array('domainId' => $domainId, 'mappingId' => $mappingId));
			}
		}
	
		return $viewModel->setVariables(
				array(
						'form' => $form,
				)
		);
	}
	
	/*public function removeAttributeAction()
	{
		$service 	= $this->getAdminObjectService();
		$objectId = $this->getEvent()->getRouteMatch()->getParam('objectId');
		$attributeId = $this->getEvent()->getRouteMatch()->getParam('attributeId');
	
		if (!$attributeId) {
			return $this->redirect()->toRoute('admin/playgroundflow/object/attribute/create', array('objectId' => $objectId));
		}
	
		$attribute = $service->getObjectAttributeMapper()->findById($attributeId);
		if ($attribute) {
			try {
				$service->getObjectAttributeMapper()->remove($attribute);
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The attribute has been removed');
			} catch (\Doctrine\DBAL\DBALException $e) {
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('This attribute is included in a story and can\'t be removed');
				//throw $e;
			}
		}
	
		return $this->redirect()->toRoute('admin/playgroundflow/object/attribute', array('objectId' => $objectId));
	}*/
	
	public function getAdminDomainService()
	{
		if (!$this->adminDomainService) {
			$this->adminDomainService = $this->getServiceLocator()->get('playgroundflow_domain_service');
		}
	
		return $this->adminDomainService;
	}
	
	public function setAdminDomainService(AdminDomainService $adminDomainService)
	{
		$this->adminDomainService = $adminDomainService;
	
		return $this;
	}
}
