<?php
namespace PlaygroundFlow\Controller\Admin;

use Laminas\Paginator\Paginator;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use PlaygroundFlow\Entity\OpenGraphDomain as Domain;
use PlaygroundFlow\Entity\OpenGraphStoryMapping as Mapping;
use PlaygroundFlow\Entity\OpenGraphObjectMapping as ObjectMapping;
use Laminas\ServiceManager\ServiceLocatorInterface;

class DomainController extends AbstractActionController
{

    /**
     *
     * @var DomainService
     */
    protected $adminDomainService;

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
        $service = $this->getAdminDomainService();
        
        $domains = $service->getDomainMapper()->findAll();
        
        if (is_array($domains)) {
            $paginator = new \Laminas\Paginator\Paginator(new \Laminas\Paginator\Adapter\ArrayAdapter($domains));
            $paginator->setItemCountPerPage(25);
            $paginator->setCurrentPageNumber($this->getEvent()
                ->getRouteMatch()
                ->getParam('p'));
        } else {
            $paginator = $domains;
        }
        
        return array(
            'domains' => $paginator
        );
    }

    public function createAction()
    {
        $service = $this->getAdminDomainService();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/admin/domain/domain');
        
        $domain = new Domain();
        
        $form = $this->getServiceLocator()->get('playgroundflow_domain_form');
        $form->bind($domain);
        $form->get('submit')->setLabel('Add');
        $form->setAttribute('action', $this->url()
            ->fromRoute('admin/playgroundflow/domain/create', array(
            'domainId' => 0
            )));
        $form->setAttribute('method', 'post');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge($this->getRequest()
                ->getPost()
                ->toArray(), $this->getRequest()
                ->getFiles()
                ->toArray());
            $domain = $service->create($data);
            if ($domain) {
                $this->flashMessenger()
                    ->setNamespace('playgroundflow')
                    ->addMessage('The domain was created');
                
                return $this->redirect()->toRoute('admin/playgroundflow/domain');
            }
        }
        
        return $viewModel->setVariables(array(
            'form' => $form
        ));
    }

    public function editAction()
    {
        $service = $this->getAdminDomainService();
        $domainId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('domainId');
        
        if (! $domainId) {
            return $this->redirect()->toRoute('admin/playgroundflow/domain/create');
        }
        
        $domain = $service->getDomainMapper()->findById($domainId);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/admin/domain/domain');
        
        $form = $this->getServiceLocator()->get('playgroundflow_domain_form');
        $form->bind($domain);
        $form->get('submit')->setLabel('Add');
        $form->setAttribute('action', $this->url()
            ->fromRoute('admin/playgroundflow/domain/edit', array(
            'domainId' => $domainId
            )));
        $form->setAttribute('method', 'post');
        $form->get('submit')->setLabel('Edit');
        
        if ($this->getRequest()->isPost()) {
            $data = array_merge($this->getRequest()
                ->getPost()
                ->toArray(), $this->getRequest()
                ->getFiles()
                ->toArray());
            $result = $service->edit($data, $domain);
            
            if ($result) {
                return $this->redirect()->toRoute('admin/playgroundflow/domain');
            }
        }
        
        return $viewModel->setVariables(array(
            'form' => $form
        ));
    }

    public function removeAction()
    {
        $service = $this->getAdminDomainService();
        $domainId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('domainId');
        
        if (! $domainId) {
            return $this->redirect()->toRoute('admin/playgroundflow/domain/create');
        }
        
        $domain = $service->getDomainMapper()->findById($domainId);
        if ($domain) {
            try {
                $service->getDomainMapper()->remove($domain);
                $this->flashMessenger()
                    ->setNamespace('playgroundflow')
                    ->addMessage('The domain has been removed');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $this->flashMessenger()
                    ->setNamespace('playgroundflow')
                    ->addMessage('This domain is included in a domain and can\'t be removed');
                // throw $e;
            }
        }
        
        return $this->redirect()->toRoute('admin/playgroundflow/domain');
    }

    public function listStoryAction()
    {
        $domainId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('domainId');
        if (! $domainId) {
            return $this->redirect()->toRoute('admin/playgroundflow/domain');
        }
        $service = $this->getAdminDomainService();
        
        $domain = $service->getDomainMapper()->findById($domainId);
        $mapping = $service->getStoryMappingMapper()->findByDomainId($domainId);
        
        if (is_array($mapping)) {
            $paginator = new \Laminas\Paginator\Paginator(new \Laminas\Paginator\Adapter\ArrayAdapter($mapping));
            $paginator->setItemCountPerPage(25);
            $paginator->setCurrentPageNumber($this->getEvent()
                ->getRouteMatch()
                ->getParam('p'));
        } else {
            $paginator = $mapping;
        }
        
        return array(
            'mapping' => $paginator,
            'domainId' => $domainId,
            'domain' => $domain
        );
    }

    public function createStoryAction()
    {
        $service = $this->getAdminDomainService();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/admin/domain/story');
        $domainId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('domainId');
        
        if (! $domainId) {
            return $this->redirect()->toRoute('admin/playgroundflow/domain');
        }
        
        $mapping = new Mapping();
        
        $form = $this->getServiceLocator()->get('playgroundflow_storymapping_form');
        $form->bind($mapping);
        $form->get('submit')->setLabel('Add');
        $form->get('domainId')->setAttribute('value', $domainId);
        $form->setAttribute('action', $this->url()
            ->fromRoute('admin/playgroundflow/domain/story/create', array(
            'domainId' => $domainId,
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
                
                return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array(
                    'domainId' => $domainId
                ));
            }
        }
        
        return $viewModel->setVariables(array(
            'form' => $form
        ));
    }

    public function editStoryAction()
    {
        $service = $this->getAdminDomainService();
        $domainId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('domainId');
        $mappingId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('mappingId');
        
        if (! $mappingId) {
            return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array(
                'domainId' => $domainId
            ));
        }
        
        $storyMapping = $service->getStoryMappingMapper()->findById($mappingId);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/admin/domain/story');
        
        $form = $this->getServiceLocator()->get('playgroundflow_storymapping_form');
        $form->bind($storyMapping);
        $form->get('domainId')->setAttribute('value', $domainId);
        $form->setAttribute('action', $this->url()
            ->fromRoute('admin/playgroundflow/domain/story/edit', array(
            'domainId' => $domainId,
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
                return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array(
                    'domainId' => $domainId
                ));
            }
        }
        
        return $viewModel->setVariables(array(
            'form' => $form
        ));
    }

    public function removeStoryAction()
    {
        $service = $this->getAdminDomainService();
        $domainId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('domainId');
        $mappingId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('mappingId');
        
        if (! $mappingId) {
            return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array(
                'domainId' => $domainId
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
        
        return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array(
            'domainId' => $domainId
        ));
    }

    public function listObjectAction()
    {
        $service = $this->getAdminDomainService();
        $domainId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('domainId');
        $mappingId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('mappingId');
        if (! $mappingId) {
            return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array(
                'domainId' => $domainId
            ));
        }
        $storyMapping = $service->getStoryMappingMapper()->findById($mappingId);
        
        $objects = $storyMapping->getObjects()->toArray();
        
        if (is_array($objects)) {
            $paginator = new \Laminas\Paginator\Paginator(new \Laminas\Paginator\Adapter\ArrayAdapter($objects));
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
            'domainId' => $domainId,
            'objects' => $paginator
        );
    }

    public function createObjectAction()
    {
        $service = $this->getAdminDomainService();
        $domainId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('domainId');
        $mappingId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('mappingId');
        $objectId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('objectId');
        if (! $mappingId) {
            return $this->redirect()->toRoute('admin/playgroundflow/domain/story', array(
                'domainId' => $domainId
            ));
        }
        $storyMapping = $service->getStoryMappingMapper()->findById($mappingId);
        $objectMapping = new ObjectMapping();
        $objectMapping->setStoryMapping($storyMapping);
        
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/admin/domain/object');
        
        $objectsArray = array();
        foreach ($storyMapping->getStory()->getObjects() as $object) {
            $objectsArray[$object->getId()] = $object->getLabel();
        }
        
        $form = $this->getServiceLocator()->get('playgroundflow_objectmapping_form');
        $form->get('object')->setAttribute('options', $objectsArray);

        $form->bind($objectMapping);
        $form->setAttribute('action', $this->url()
            ->fromRoute('admin/playgroundflow/domain/story/object/create', array(
            'domainId' => $domainId,
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
                return $this->redirect()->toRoute('admin/playgroundflow/domain/story/object', array(
                    'domainId' => $domainId,
                    'mappingId' => $mappingId
                ));
            }
        }
        
        return $viewModel->setVariables(array(
            'form' => $form,
            'domainId' => $domainId,
            'mappingId' => $mappingId
        ));
    }

    public function editObjectAction()
    {
        $service = $this->getAdminDomainService();
        $domainId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('domainId');
        $mappingId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('mappingId');
        $objectId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('objectId');
        if (! $mappingId) {
            return $this->redirect()->toRoute(
                'admin/playgroundflow/domain/story',
                array(
                    'domainId' => $domainId
                )
            );
        }
        $storyMapping = $service->getStoryMappingMapper()->findById($mappingId);
        $objectMapping = $service->getObjectMappingMapper()->findById($objectId);
        
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-flow/admin/domain/object');
        
        $objectsArray = array();
        foreach ($storyMapping->getStory()->getObjects() as $object) {
            $objectsArray[$object->getId()] = $object->getLabel();
        }
        
        $form = $this->getServiceLocator()->get('playgroundflow_objectmapping_form');
        $form->get('object')->setAttribute('options', $objectsArray);
        $form->bind($objectMapping);
        $form->setAttribute(
            'action',
            $this->url()->fromRoute(
                'admin/playgroundflow/domain/story/object/edit',
                array(
                    'domainId' => $domainId,
                    'mappingId' => $mappingId,
                    'objectId' => $objectMapping->getId()
                )
            )
        );
        $form->setAttribute('method', 'post');
        $form->get('submit')->setLabel('Edit');
        
        if ($this->getRequest()->isPost()) {
            $data = array_merge(
                $this->getRequest()
                    ->getPost()
                    ->toArray(),
                $this->getRequest()
                    ->getFiles()
                    ->toArray()
            );
            $result = $service->editObject($data, $objectMapping);
            
            if ($result) {
                return $this->redirect()->toRoute(
                    'admin/playgroundflow/domain/story/object',
                    array(
                        'domainId' => $domainId,
                        'mappingId' => $mappingId
                    )
                );
            }
        }
        
        return $viewModel->setVariables(
            array(
                'form' => $form,
                'domainId' => $domainId,
                'mappingId' => $mappingId
            )
        );
    }

    public function removeObjectAction()
    {
        $service = $this->getAdminDomainService();
        $domainId = $this->getEvent()
        ->getRouteMatch()
        ->getParam('domainId');
        $mappingId = $this->getEvent()
        ->getRouteMatch()
        ->getParam('mappingId');
        $objectId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('objectId');
        
        if (! $objectId) {
            return $this->redirect()->toRoute('admin/playgroundflow/domaine/story/object', array(
                'domainId' => $domainId,
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
        
        return $this->redirect()->toRoute('admin/playgroundflow/domain/story/object', array(
            'domainId' => $domainId,
            'mappingId' => $mappingId,
        ));
    }
    
    /*
     * public function removeAttributeAction() { $service 	= $this->getAdminObjectService(); $objectId = $this->getEvent()->getRouteMatch()->getParam('objectId'); $attributeId = $this->getEvent()->getRouteMatch()->getParam('attributeId'); if (!$attributeId) { return $this->redirect()->toRoute('admin/playgroundflow/object/attribute/create', array('objectId' => $objectId)); } $attribute = $service->getObjectAttributeMapper()->findById($attributeId); if ($attribute) { try { $service->getObjectAttributeMapper()->remove($attribute); $this->flashMessenger()->setNamespace('playgroundflow')->addMessage('The attribute has been removed'); } catch (\Doctrine\DBAL\DBALException $e) { $this->flashMessenger()->setNamespace('playgroundflow')->addMessage('This attribute is included in a story and can\'t be removed'); //throw $e; } } return $this->redirect()->toRoute('admin/playgroundflow/object/attribute', array('objectId' => $objectId)); }
     */
    public function getAdminDomainService()
    {
        if (! $this->adminDomainService) {
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
