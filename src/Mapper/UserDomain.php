<?php

namespace PlaygroundFlow\Mapper;

use Doctrine\ORM\EntityManager;
use Laminas\Hydrator\HydratorInterface;


class UserDomain
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function findById($id)
    {
        return $this->getRepository()->find($id);
    }

    public function findBy($filters)
    {
        return $this->getRepository()->findBy($filters);
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
        return $this->getRepository()->findAll();
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function getRepository()
    {
        return $this->em->getRepository('\PlaygroundFlow\Entity\OpenGraphUserDomain');
    }
}
