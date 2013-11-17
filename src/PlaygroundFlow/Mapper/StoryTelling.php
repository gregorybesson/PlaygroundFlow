<?php

namespace PlaygroundFlow\Mapper;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcBase\Mapper\AbstractDbMapper;
use Zend\Stdlib\Hydrator\HydratorInterface;
use PlaygroundFlow\Options\ModuleOptions;

class StoryTelling implements ServiceLocatorAwareInterface
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

    public function findBy($array)
    {
        return $this->getEntityRepository()->findBy($array);
    }

    public function findOneBy($array=array(), $sortBy = array('updated_at' => 'desc'))
    {
        $er = $this->getEntityRepository();

        return $er->findOneBy($array, $sortBy);
    }
    
    public function findOneBySecretKey($secretKey)
    {
        $er = $this->getEntityRepository();
    
        return $er->findOneBy(array('secretKey' => $secretKey));
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

    public function findWithStoryMappingByUser($user) 
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select(array('r'))
        ->from('PlaygroundFlow\Entity\OpenGraphStoryTelling', 'r')
        ->join('r.openGraphStoryMapping', 's')
        ->join('s.story', 'st')
        ->join('st.objects', 'o')
        ->where('r.user = :user')
        ->orderBy("r.id", "DESC")
        ->setParameter('user', $user->getId());
        
        return $qb->getQuery()->getResult();

    }

    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundFlow\Entity\OpenGraphStoryTelling');
        }

        return $this->er;
    }
    
    /**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}