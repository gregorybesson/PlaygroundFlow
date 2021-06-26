<?php

namespace PlaygroundFlowTest\Service;

use PlaygroundFlowTest\Bootstrap;
use PlaygroundFlow\Entity\OpenGraphDomain;
use PlaygroundUser\Entity\User;
use PlaygroundFlow\Entity\OpenGraphProspect as ProspectEntity;

class ProspectTest extends \PHPUnit\Framework\TestCase
{
    protected $traceError = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sm = Bootstrap::getServiceManager();
    }

    public function testGetProspectMapper()
    {
        $prospectService = $this->sm->get('playgroundflow_prospect_service');
        $mapper = $prospectService->getProspectMapper();
        $this->assertEquals(get_class($mapper), 'PlaygroundFlow\Mapper\Prospect');
    }

    public function testFindProspectOrCreateByProspectAndDomain()
    {
        $prospectService = $this->sm->get('playgroundflow_prospect_service');

        $domain = new OpenGraphDomain();
        $domain->setId('1')
            ->setTitle('Demo test')
            ->setDomain('http://pmagento.local');

        $prospectId = "prospect_123456789";
        $prospect = new ProspectEntity();
        $prospect->setDomain($domain)
            ->setProspect($prospectId);


        $mapper = $this->getMockBuilder('PlaygroundFlow\Mapper\Prospect')
            ->disableOriginalConstructor()
            ->getMock();
        $mapper->expects($this->any())
            ->method('insert')
            ->will($this->returnValue($prospect));

        $prospectService->setProspectMapper($mapper);

        $prospects = $prospectService->findProspectOrCreateByProspectAndDomain($prospect, $domain);
        $this->assertEquals(count([$prospects]), 1);

        $mapper->expects($this->any())
            ->method('findBy')
            ->will($this->returnValue(array($prospect)));

        $prospectService->setProspectMapper($mapper);

        $prospects = $prospectService->findProspectOrCreateByProspectAndDomain($prospect, $domain);
        $this->assertEquals(count([$prospects]), 1);
    }
}
