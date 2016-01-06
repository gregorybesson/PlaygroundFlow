<?php

namespace PlaygroundFlowTest\Service;

use PlaygroundFlowTest\Bootstrap;
use PlaygroundFlow\Entity\OpenGraphDomain;
use PlaygroundUser\Entity\User;
use PlaygroundFlow\Entity\OpenGraphUserDomain as UserDomainEntity;

class UserDomainTest extends \PHPUnit_Framework_TestCase
{
    protected $traceError = true;

    public function setUp()
    {
        parent::setUp();
        $this->sm = Bootstrap::getServiceManager();
    }

    public function testGetUserDomainMapper()
    {
        $userDomainService = $this->sm->get('playgroundflow_user_domain_service');
        $userDomainMapper = $userDomainService->getUserDomainMapper();
        $this->assertEquals(get_class($userDomainMapper), 'PlaygroundFlow\Mapper\UserDomain');

    }

    public function testFindUserDomainOrCreateByUserAndDomain()
    {
        $userDomainService = $this->sm->get('playgroundflow_user_domain_service');

        $this->userData = array(
            'username'  => 'troger',
            'email' => 'thomas.roger@adfab.fr',
            'displayName' => 'troger',
            'password' => 'troger',
            'state' => '0',
            'firstname' => 'thomas',
            'lastname' => 'roger',
            'optin' => '1',
            'optinPartner' => '0',
        );

        $user = new User;
        $user->setId('1');
        foreach ($this->userData as $key => $value) {
            $method = 'set'.ucfirst($key);
            $user->$method($value);
        }

        $domain = new OpenGraphDomain();
        $domain->setId('1')
            ->setTitle('Demo test')
            ->setDomain('http://pmagento.local');

        $userDomain = new UserDomainEntity();
        $userDomain->setDomain($domain)
            ->setUser($user);

        $mapper = $this->getMockBuilder('PlaygroundFlow\Mapper\UserDomain')
            ->disableOriginalConstructor()
            ->getMock();
        $mapper->expects($this->any())
            ->method('insert')
            ->will($this->returnValue($userDomain));

        $userDomainService->setUserDomainMapper($mapper);

        $userDomain = $userDomainService->findUserDomainOrCreateByUserAndDomain($user, $domain);
        $this->assertEquals(count($userDomain), 1);

         $mapper->expects($this->any())
            ->method('findBy')
            ->will($this->returnValue(array($userDomain)));

        $userDomainService->setUserDomainMapper($mapper);

        $userDomain = $userDomainService->findUserDomainOrCreateByUserAndDomain($user, $domain);
        $this->assertEquals(count($userDomain), 1);
    }
}
