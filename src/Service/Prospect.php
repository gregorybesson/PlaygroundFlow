<?php

namespace PlaygroundFlow\Service;

use Laminas\ServiceManager\ServiceManager;
use PlaygroundFlow\Entity\OpenGraphProspect as ProspectEntity;
use Laminas\ServiceManager\ServiceLocatorInterface;

class Prospect
{
    /**
    * @var Mapper\Prospect $prospectMapper
    */
    protected $prospectMapper = null;

    /**
     *
     * @var ServiceManager
     */
    protected $serviceLocator;

    public function __construct(ServiceLocatorInterface $locator)
    {
        $this->serviceLocator = $locator;
    }

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
            $this->prospectMapper = $this->serviceLocator->get('playgroundflow_prospect_mapper');
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
}
