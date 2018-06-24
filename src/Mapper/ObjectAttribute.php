<?php

namespace PlaygroundFlow\Mapper;

use Doctrine\ORM\EntityManager;

use Zend\Hydrator\HydratorInterface;
use PlaygroundFlow\Options\ModuleOptions;

class ObjectAttribute
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
     * @var \PlaygroundFlow\Options\ModuleOptions
     */
    protected $options;

    public function __construct(EntityManager $em, ModuleOptions $options)
    {
        $this->em      = $em;
        $this->options = $options;
    }

    public function findById($id)
    {
        return $this->getEntityRepository()->find($id);
    }

    public function findByObjectId($object)
    {
        return $this->getEntityRepository()->findBy(array('object' => $object));
    }
    
    public function findBy($array)
    {
        return $this->getEntityRepository()->findBy($array);
    }

    public function findOneBy($array = array(), $sortBy = array('updated_at' => 'desc'))
    {
        $er = $this->getEntityRepository();

        return $er->findOneBy($array, $sortBy);
    }

    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }
    
    public function insert($entity)
    {
        return $this->persist($entity);
    }

    public function update($entity)
    {
        return $this->persist($entity);
    }

    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundFlow\Entity\OpenGraphObjectAttribute');
        }

        return $this->er;
    }
}
