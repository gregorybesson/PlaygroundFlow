<?php

namespace PlaygroundFlowTest\Mapper;

use Zend\Crypt\Password\Bcrypt;
use PlaygroundFlowTest\Bootstrap;
use PlaygroundFlow\Entity\OpenGraphDomain;
use PlaygroundUser\Entity\User;
use PlaygroundFlow\Entity\OpenGraphProspect as ProspectEntity;

class Prospect extends \PHPUnit_Framework_TestCase
{
    protected $traceError = true;

    protected $userDomainData;


    public function setUp()
    {
        $this->sm = Bootstrap::getServiceManager();
        $this->em = $this->sm->get('doctrine.entitymanager.orm_default');
        $this->tm = $this->sm->get('playgroundflow_prospect_mapper');
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


    public function testProspect()
    {
        $prospectId = "prospect_123456789";
        $prospect = new ProspectEntity();
        $prospect->setDomain($this->domain)
            ->setProspect($prospectId);
        $prospect = $this->tm->insert($prospect);
        $this->assertEquals($prospectId, $prospect->getProspect());

        $prospectId = "prospect_123456789";
        $prospect2 = new ProspectEntity();
        $prospect2->setDomain($this->domain2)
            ->setProspect($prospectId);
        $prospect2 = $this->tm->insert($prospect2);
        $this->assertEquals($prospectId, $prospect2->getProspect());


        $prospects = $this->tm->findAll();
        $this->assertEquals(count($prospects), 2);

        $prospects = $this->tm->findBy(array('domain' => $this->domain2));
        $this->assertEquals(count($prospects), 1);
        $this->assertEquals($prospects[0]->getDomain()->getId(), $this->domain2->getId());

        $prospects = $this->tm->findById($prospect->getId());
        $this->assertEquals(count($prospects), 1);
        $this->assertEquals($prospects->getId(), 1);

        $prospect2->setDomain($this->domain);
        $prospect2 = $this->tm->update($prospect2);
        $this->assertEquals($prospect2->getDomain()->getId(), $this->domain->getId());

        $this->tm->remove($prospect2);
        $this->tm->remove($prospect);
        $prospects = $this->tm->findAll();
        $this->assertEquals(count($prospects), 0);

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
