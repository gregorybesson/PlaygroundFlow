<?php

namespace PlaygroundFlow\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

use PlaygroundFlow\Entity\OpenGraphProspect as ProspectEntity;

class Prospect implements ServiceManagerAwareInterface
{
    /**
    * @var Mapper\Prospect $prospectMapper
    */
    protected $prospectMapper = null;
    /**
    * findProspectOrCreateByProspectAndDomain : retrieve prospect with prospect and domain of create if not exits
    * @param string $prospect
    * @param Domain $domain
    *
    * @return Prospect $prospectEntity
    */
    public function findProspectOrCreateByProspectAndDomain($prospect, $domain)
    {
        $prospects = $this->getProspectMapper()->findBy(array('prospect' => $prospect, 'domain' => $domain));
        if (!empty($prospects)) {
            $prospectEntity = $prospects[0];
        } else {
            // Pas de prospect : alors on en crée un 
            $prospectEntity = new ProspectEntity();
            $prospectEntity->setDomain($domain)
                ->setProspect($prospect);
            $prospectEntity = $this->getProspectMapper()->insert($prospectEntity);
        }
        return $prospectEntity;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getProspectMapper()
    {
        if ($this->prospectMapper == null) {
            $this->prospectMapper = $this->getServiceManager()->get('playgroundflow_prospect_mapper');
        }

        return $this->prospectMapper;
    }

    /**
     * set prospect mapper instance
     *
     * @return ServiceManager
     */
    public function setProspectMapper($mapper)
    {
        $this->prospectMapper = $mapper;

        return $this;
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
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}