<?php

namespace PlaygroundFlow\Service;

use Zend\ServiceManager\ServiceManager;
use PlaygroundFlow\Entity\OpenGraphUserDomain as UserDomainEntity;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserDomain
{
    /**
    * @var Mapper\UserDomain $userDomainMapper
    */
    protected $userDomainMapper = null;
    
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
    * findUserDomainOrCreateByUserAndDomain : retrieve userDomain with user and domain of create if not exits
    * @param User $user
    * @param Domain $domain
    *
    * @return UserDomain $userDomain
    */
    public function findUserDomainOrCreateByUserAndDomain($user, $domain)
    {
        $userDomain = $this->getUserDomainMapper()->findBy(array('user' => $user, 'domain' => $domain));
        if (empty($userDomain)) {
            $userDomain = new UserDomainEntity();
            $userDomain->setDomain($domain)
                ->setUser($user);
            $userDomain = $this->getUserDomainMapper()->insert($userDomain);
        }

        return $userDomain;
    }
  
  
    public function getUserDomainMapper()
    {
        if ($this->userDomainMapper == null) {
            $this->userDomainMapper = $this->serviceLocator->get('playgroundflow_user_domain_mapper');
        }

        return $this->userDomainMapper;
    }

     /**
     * set prospect mapper instance
     *
     * @return ServiceManager
     */
    public function setUserDomainMapper($mapper)
    {
        $this->userDomainMapper = $mapper;

        return $this;
    }
}
