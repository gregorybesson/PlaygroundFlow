<?php

namespace PlaygroundFlow\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundFlow\Options\ModuleOptions;

class Widget extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var WidgetMapperInterface
     */
    protected $widgetMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var WidgetServiceOptionsInterface
     */
    protected $options;

    public function create(array $data)
    {
        $widget  = new \PlaygroundFlow\Entity\OpenGraphWidget();
        $form  = $this->getServiceManager()->get('playgroundflow_widget_form');
        $form->bind($widget);
        $form->setData($data);
        
        if (!$form->isValid()) {
            return false;
        }
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('widget' => $widget, 'data' => $data));
        $this->getWidgetMapper()->insert($widget);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('widget' => $widget, 'data' => $data));
        
        return $widget;
    }

    public function edit(array $data, $widget)
    {
        $form  = $this->getServiceManager()->get('playgroundflow_widget_form');
        $form->bind($widget);
        $form->setData($data);
         
        if (!$form->isValid()) {
            return false;
        }
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('widget' => $widget, 'data' => $data));
        $this->getWidgetMapper()->update($widget);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('widget' => $widget, 'data' => $data));

        return $widget;
    }

    /**
     * getWidgetMapper
     *
     * @return WidgetMapperInterface
     */
    public function getWidgetMapper()
    {
        if (null === $this->widgetMapper) {
            $this->widgetMapper = $this->getServiceManager()->get('playgroundflow_widget_mapper');
        }

        return $this->widgetMapper;
    }

    /**
     * setWidgetMapper
     *
     * @param  WidgetMapperInterface $widgetMapper
     * @return Widget
     */
    public function setWidgetMapper(WidgetMapperInterface $widgetMapper)
    {
        $this->widgetMapper = $widgetMapper;

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
            $this->setOptions($this->getServiceManager()->get('playgroundflow_module_options'));
        }

        return $this->options;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param  ServiceManager $locator
     * @return Widget
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
