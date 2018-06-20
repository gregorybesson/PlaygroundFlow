<?php

namespace PlaygroundFlow\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventManagerAwareTrait;
use PlaygroundFlow\Options\ModuleOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

class Action
{
    use EventManagerAwareTrait;

    /**
     * @var ActionMapperInterface
     */
    protected $actionMapper;

    /**
     * @var ActionServiceOptionsInterface
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

    public function create(array $data)
    {
        $action  = new \PlaygroundFlow\Entity\OpenGraphAction();
        $form  = $this->serviceLocator->get('playgroundflow_action_form');
        $form->bind($action);
        $form->setData($data);
        
        if (!$form->isValid()) {
            return false;
        }
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('action' => $action, 'data' => $data));
        $this->getActionMapper()->insert($action);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('action' => $action, 'data' => $data));
        
        return $action;
    }

    public function edit(array $data, $action)
    {
        $form  = $this->serviceLocator->get('playgroundflow_action_form');
        $form->bind($action);
        $form->setData($data);
         
        if (!$form->isValid()) {
            return false;
        }
        
        $this->getActionMapper()->update($action);
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('action' => $action, 'data' => $data));
        $this->getActionMapper()->insert($action);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('action' => $action, 'data' => $data));

        return $action;
    }

    /**
     * getActionMapper
     *
     * @return ActionMapperInterface
     */
    public function getActionMapper()
    {
        if (null === $this->actionMapper) {
            $this->actionMapper = $this->serviceLocator->get('playgroundflow_action_mapper');
        }

        return $this->actionMapper;
    }

    /**
     * setActionMapper
     *
     * @param  ActionMapperInterface $actionMapper
     * @return Action
     */
    public function setActionMapper(ActionMapperInterface $actionMapper)
    {
        $this->actionMapper = $actionMapper;

        return $this;
    }

    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->serviceLocator->get('playgroundflow_module_options'));
        }

        return $this->options;
    }
}
