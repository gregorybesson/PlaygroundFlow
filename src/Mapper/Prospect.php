<?php

namespace PlaygroundFlow\Mapper;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Hydrator\HydratorInterface;
use PlaygroundFlow\Options\ModuleOptions;


class Prospect
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $er;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var \PlaygroundGame\Options\ModuleOptions
     */
    protected $options;

    public function __construct(EntityManager $em, ModuleOptions $options, ServiceLocatorInterface $locator)
    {
        $this->em      = $em;
        $this->options = $options;
        $this->serviceLocator = $locator;
    }

    public function findBy($filters)
    {
        return $this->getEntityRepository()->findBy($filters);
    }

    public function findOneBy($filters)
    {
        return $this->getEntityRepository()->findOneBy($filters);
    }


    public function findById($id)
    {
        return $this->getEntityRepository()->find($id);
    }

    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->persist($entity);
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->persist($entity);
    }

    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('\PlaygroundFlow\Entity\OpenGraphProspect');
        }

        return $this->er;
    }
}
