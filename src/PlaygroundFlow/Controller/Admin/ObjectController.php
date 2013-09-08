<?php

namespace PlaygroundFlow\Controller\Admin;

use Zend\Paginator\Paginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use PlaygroundFlow\Entity\OpenGraphObject as Object;
use PlaygroundFlow\Entity\OpenGraphObjectAttribute as Attribute;

class ObjectController extends AbstractActionController
{
	/**
	 * @var ObjectService
	 */
	protected $adminObjectService;
	
	public function listAction()
	{
	
		$service 	= $this->getAdminObjectService();

		$objects = $service->getObjectMapper()->findAll();
		
		if (is_array($objects)) {
			$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($objects));
			$paginator->setItemCountPerPage(25);
			$paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
		} else {
			$paginator = $objects;
		}

		return array(
			'objects' 	=> $paginator,
		);
	}
	
	public function createAction()
	{
		$service 	= $this->getAdminObjectService();
		$viewModel = new ViewModel();
		$viewModel->setTemplate('playground-flow/admin/object/object');
	
		$object = new Object();
	
		$form = $this->getServiceLocator()->get('playgroundflow_object_form');
		$form->bind($object);
		$form->get('submit')->setLabel('Add');
		$form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/object/create', array('objectId' => 0)));
		$form->setAttribute('method', 'post');
	
		$request = $this->getRequest();
		if ($request->isPost()) {
			$data = array_merge(
				$this->getRequest()->getPost()->toArray(),
				$this->getRequest()->getFiles()->toArray()
			);
			$object = $service->create($data);
			if ($object) {
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The object was created');
	
				return $this->redirect()->toRoute('admin/playgroundflow/object');
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
		$service 	= $this->getAdminObjectService();
		$objectId = $this->getEvent()->getRouteMatch()->getParam('objectId');
	
		if (!$objectId) {
			return $this->redirect()->toRoute('admin/playgroundflow/object/create');
		}
	
		$object = $service->getObjectMapper()->findById($objectId);
		$viewModel = new ViewModel();
		$viewModel->setTemplate('playground-flow/admin/object/object');
	
		$form = $this->getServiceLocator()->get('playgroundflow_object_form');
		$form->bind($object);
		$form->get('submit')->setLabel('Add');
		$form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/object/edit', array('objectId' => $objectId)));
		$form->setAttribute('method', 'post');
		$form->get('submit')->setLabel('Edit');
	
		if ($this->getRequest()->isPost()) {
			$data = array_merge(
					$this->getRequest()->getPost()->toArray(),
					$this->getRequest()->getFiles()->toArray()
			);
			$result = $service->edit($data, $object);
	
			if ($result) {
				return $this->redirect()->toRoute('admin/playgroundflow/object');
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
		$service 	= $this->getAdminObjectService();
		$objectId = $this->getEvent()->getRouteMatch()->getParam('objectId');
	
		if (!$objectId) {
			return $this->redirect()->toRoute('admin/playgroundflow/object/create');
		}
	
		$object = $service->getObjectMapper()->findById($objectId);
		if ($object) {
			try {
				$service->getObjectMapper()->remove($object);
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The object has been removed');
			} catch (\Doctrine\DBAL\DBALException $e) {
				$this->flashMessenger()->setNamespace('playgroundflow')->addMessage('This object is included in a story and can\'t be removed');
				//throw $e;
			}
		}
	
		return $this->redirect()->toRoute('admin/playgroundflow/object');
	}
	
	public function listAttributeAction()
	{
		$objectId = $this->getEvent()->getRouteMatch()->getParam('objectId');
		if (!$objectId) {
			return $this->redirect()->toRoute('admin/playgroundflow/object');
		}
		$service 	= $this->getAdminObjectService();

		$object = $service->getObjectMapper()->findById($objectId);
		$attributes = $service->getObjectAttributeMapper()->findByObjectId($objectId);
	
		if (is_array($attributes)) {
			$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($attributes));
			$paginator->setItemCountPerPage(25);
			$paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
		} else {
			$paginator = $attributes;
		}
	
		return array(
			'attributes' 	=> $paginator,
			'objectId' 		=> $objectId,
			'object' 		=> $object,
		);
	}
	
	public function createAttributeAction()
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
	}
	
	public function editAttributeAction()
	{
		$service 	= $this->getAdminObjectService();
		$objectId = $this->getEvent()->getRouteMatch()->getParam('objectId');
		$attributeId = $this->getEvent()->getRouteMatch()->getParam('attributeId');
	
		if (!$attributeId) {
			return $this->redirect()->toRoute('admin/playgroundflow/object/attribute', array('objectId' => $objectId));
		}
	
		$attribute = $service->getObjectAttributeMapper()->findById($attributeId);
		$viewModel = new ViewModel();
		$viewModel->setTemplate('playground-flow/admin/object/attribute');
	
		$form = $this->getServiceLocator()->get('playgroundflow_objectattribute_form');
		$form->bind($attribute);
		$form->setAttribute('action', $this->url()->fromRoute('admin/playgroundflow/object/attribute/edit', array('objectId' => $objectId, 'attributeId' => $attributeId)));
		$form->setAttribute('method', 'post');
		$form->get('submit')->setLabel('Edit');
	
		if ($this->getRequest()->isPost()) {
			$data = array_merge(
					$this->getRequest()->getPost()->toArray(),
					$this->getRequest()->getFiles()->toArray()
			);
			$result = $service->editAttribute($data, $attribute);
	
			if ($result) {
				return $this->redirect()->toRoute('admin/playgroundflow/object/attribute', array('objectId' => $objectId));
			}
		}
	
		return $viewModel->setVariables(
			array(
				'form' => $form,
			)
		);
	}
	
	public function removeAttributeAction()
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
	}
	
	public function getAdminObjectService()
	{
		if (!$this->adminObjectService) {
			$this->adminObjectService = $this->getServiceLocator()->get('playgroundflow_object_service');
		}
	
		return $this->adminObjectService;
	}
	
	public function setAdminObjectService(AdminObjectService $adminObjectService)
	{
		$this->adminObjectService = $adminObjectService;
	
		return $this;
	}
}
