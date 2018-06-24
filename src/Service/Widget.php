<?php

namespace PlaygroundFlow\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventManagerAwareTrait;
use PlaygroundFlow\Options\ModuleOptions;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager;

class Widget
{
    use EventManagerAwareTrait;

    /**
     * @var WidgetMapperInterface
     */
    protected $widgetMapper;

    /**
     * @var WidgetServiceOptionsInterface
     */
    protected $options;

    /**
     *
     * @var ServiceManager
     */
    protected $serviceLocator;

    protected $event;

    public function __construct(ServiceLocatorInterface $locator)
    {
        $this->serviceLocator = $locator;
    }

    public function create(array $data)
    {
        $widget  = new \PlaygroundFlow\Entity\OpenGraphWidget();
        $form  = $this->serviceLocator->get('playgroundflow_widget_form');
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
        $form  = $this->serviceLocator->get('playgroundflow_widget_form');
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
            $this->widgetMapper = $this->serviceLocator->get('playgroundflow_widget_mapper');
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
            $this->setOptions($this->serviceLocator->get('playgroundflow_module_options'));
        }

        return $this->options;
    }

    public function getEventManager()
    {
        if ($this->event === NULL) {
            $this->event = new EventManager(
                $this->serviceLocator->get('SharedEventManager'), [get_class($this)]
            );
        }
        return $this->event;
    }
}
