<?php

namespace PlaygroundFlow\Mapper;

use Doctrine\ORM\EntityManager;

use Zend\Hydrator\HydratorInterface;
use PlaygroundFlow\Options\ModuleOptions;

class Event implements ActionInterface
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
        $er = $this->em->getEntityRepository();

        return $er->find($id);
    }

    public function findOneBySecretKey($secretKey)
    {
        $er = $this->getEntityRepository();

        return $er->findOneBy(array('secretKey' => $secretKey));
    }

    public function findBy($array, $sort = array())
    {
        $er = $this->getEntityRepository();

        return $er->findBy($array, $sort);
    }

    public function findActivity($user, $type = '')
    {
        switch ($type) {
            case 'game':
                $filter = array(12);
                break;
            case 'user':
                $filter = array(1,2,3,4,5,6,7,8,9,10,11);
                break;
            case 'social':
                $filter = array(13,14,15,16,17,20);
                break;
            case 'sponsor':
                $filter = array(20);
                break;
            case 'badges':
                $filter = array(100, 101, 102);
                break;
            default:
                $filter = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,100,101,102);
        }

        $query = $this->em->createQuery('SELECT e FROM PlaygroundFlow\Entity\Event e WHERE e.user = :user AND e.points > 0 and e.actionId in (?1) ORDER BY e.createdAt DESC');
        $query->setParameter('user', $user);
        $query->setParameter(1, $filter);

        return $events = $query->getResult();
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
        $er = $this->getEntityRepository($this->getRepository());

        return $er->findAll();
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundFlow\Entity\Event');
        }

        return $this->er;
    }
}
