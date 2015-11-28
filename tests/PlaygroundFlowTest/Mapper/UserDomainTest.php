<?php

namespace PlaygroundFlowTest\Mapper;

use Zend\Crypt\Password\Bcrypt;
use PlaygroundFlowTest\Bootstrap;
use PlaygroundFlow\Entity\OpenGraphDomain;
use PlaygroundUser\Entity\User;
use PlaygroundFlow\Entity\OpenGraphUserDomain as UserDomain;

class UserDomainTest extends \PHPUnit_Framework_TestCase
{
    protected $traceError = true;

    protected $userDomainData;


    public function setUp()
    {
        $this->sm = Bootstrap::getServiceManager();
        $this->em = $this->sm->get('doctrine.entitymanager.orm_default');
        $this->tm = $this->sm->get('playgroundflow_user_domain_mapper');
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $classes = $this->em->getMetadataFactory()->getAllMetadata();
        $tool->dropSchema($classes);
        $tool->createSchema($classes);

        $this->dm = $this->sm->get('playgroundflow_domain_mapper');
        $domain = new OpenGraphDomain();
        $domain->setTitle('Demo test')
            ->setDomain('http://pmagento.local');
        $domain = $this->dm->insert($domain);
        $this->domain = $domain;
        
        $domain2 = new OpenGraphDomain();
        $domain2->setTitle('Demo test 2')
            ->setDomain('http://pmagento2.local');
        $domain2 = $this->dm->insert($domain2);
        $this->domain2 = $domain2;

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
        foreach ($this->userData as $key => $value) {
            $method = 'set'.ucfirst($key);
            $user->$method($value);
        }

        $this->user = $this->tm->insert($user);

        parent::setUp();
    }


    public function testUserDomain()
    {
        $userDomain = new UserDomain();
        $userDomain->setUser($this->user)
            ->setDomain($this->domain);
        $userDomain = $this->tm->insert($userDomain);
        $this->assertEquals($this->user->getId(), $userDomain->getUser()->getId());

        $userDomain2 = new UserDomain();
        $userDomain2->setUser($this->user)
            ->setDomain($this->domain2);
        $userDomain2 = $this->tm->insert($userDomain2);
        $this->assertEquals($this->domain2->getId(), $userDomain2->getDomain()->getId());


        $userDomains = $this->tm->findAll();
        $this->assertEquals(count($userDomains), 2);

        $userDomains = $this->tm->findBy(array('domain' => $this->domain2));
        $this->assertEquals(count($userDomains), 1);
        $this->assertEquals($userDomains[0]->getDomain()->getId(), $this->domain2->getId());

        $userDomain = $this->tm->findById($userDomain->getId());
        $this->assertEquals(count($userDomain), 1);
        $this->assertEquals($userDomain->getId(), 1);

        $userDomain2->setDomain($this->domain);
        $userDomain2 = $this->tm->update($userDomain2);
        $this->assertEquals($userDomain2->getDomain()->getId(), $this->domain->getId());

        $this->tm->remove($userDomain2);
        $this->tm->remove($userDomain);
        $userDomains = $this->tm->findAll();
        $this->assertEquals(count($userDomains), 0);

    }
 

    public function tearDown()
    {
        $dbh = $this->em->getConnection();
        unset($this->tm);
        unset($this->sm);
        unset($this->em);
        unset($this->dm);
        unset($this->um);
        parent::tearDown();
    }
}
