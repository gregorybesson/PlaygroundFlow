<?php
namespace PlaygroundFlow\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * An Open Graph Action.
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="opengraph_application")
 */
class OpenGraphApplication
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="app_key", type="string", length=255, unique=true, nullable=false)
     */
    protected $appKey;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $label;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $definition;
    
    /**
     * @ORM\ManyToMany(targetEntity="PlaygroundFlow\Entity\OpenGraphDomain", inversedBy="apps")
     */
    protected $domains;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->domains = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /** @PrePersist */
    public function createChrono()
    {
        $this->createdAt = new \DateTime("now");
        $this->updatedAt = new \DateTime("now");
    }

    /** @PreUpdate */
    public function updateChrono()
    {
        $this->updatedAt = new \DateTime("now");
    }

    /**
     *
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param field_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     *
     * @return the $appKey
     */
    public function getAppKey()
    {
        return $this->appKey;
    }

    /**
     *
     * @param field_type $appKey
     */
    public function setAppKey($appKey)
    {
        $this->appKey = $appKey;
        
        return $this;
    }

    /**
     * @return the $domains
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * @param field_type $domains
     */
    public function setDomains($domains)
    {
        $this->domains = $domains;
        
        return $this;
    }

    /**
     * @return the $label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param field_type $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
        
        return $this;
    }

    /**
     * @return the $user
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param field_type $definition
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
        
        return $this;
    }

    /**
     * @return the $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }

    /**
     * @return the $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
    }
}
