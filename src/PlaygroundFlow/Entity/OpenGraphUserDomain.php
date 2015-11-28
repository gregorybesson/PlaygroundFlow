<?php

namespace PlaygroundFlow\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;

/**
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="opengraph_user_domain")
 */
class OpenGraphUserDomain
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    

    /**
     * @ORM\ManyToOne(targetEntity="PlaygroundUser\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="PlaygroundFlow\Entity\OpenGraphDomain")
     * @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     */
    protected $domain;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;


    /** @PrePersist */
    public function createChrono()
    {
        $this->setCreatedAt(new \DateTime("now"));
        $this->setUpdatedAt(new \DateTime("now"));
    }
     
    /**
     * Getter for id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Setter for id
     *
     * @param mixed $id Value to set
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
     
    /**
     * Getter for user
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Setter for user
     *
     * @param mixed $user Value to set
     * @return self
     */
    public function setUser($user)
    {
        $this->user = $user;
        
        return $this;
    }
     
    /**
     * Getter for dommain_id
     *
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }
    
    /**
     * Setter for domain
     *
     * @param mixed $domain Value to set
     * @return self
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        
        return $this;
    }
    


    /**
     * @param mixed $createdAt
     * @return Theme
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return date
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $updatedAt
     * @return Theme
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }
    /*
     * @return date
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
