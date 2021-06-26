<?php
namespace PlaygroundFlow\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\Factory as InputFactory;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

/**
 * An Open Graph Story.
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="opengraph_story")
 */
class OpenGraphStory
{
    protected $inputFilter;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $label;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $definition;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenGraphAction")
     */
    protected $action;

    /**
     * @ORM\ManyToMany(targetEntity="OpenGraphObject", cascade={"persist","remove"})
     * @ORM\JoinTable(name="opengraph_story_object",
     *      joinColumns={@ORM\JoinColumn(name="story_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="object_id", referencedColumnName="id")}
     *      )
     */
    protected $objects;
    
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
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return the $code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     *
     * @param field_type $code
     */
    public function setCode($code)
    {
        $this->code = $code;
        
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
     * @return the $action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param field_type $action
     */
    public function setAction($action)
    {
        $this->action = $action;
        
        return $this;
    }

    /**
     * @return the $objects
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $objects
     */
    public function setObjects($objects)
    {
        $this->objects = $objects;
        
        return $this;
    }
    
    /**
     * Add an object to the story.
     *
     * @param Object $object
     *
     * @return void
     */
    public function addObject($object)
    {
        $this->objects[] = $object;
    }
    
    public function addObjects(\Doctrine\Common\Collections\ArrayCollection $objects)
    {
        foreach ($objects as $object) {
            //$object->setStory($this);
            $this->objects->add($object);
        }
    }
    
    public function removeObjects(\Doctrine\Common\Collections\ArrayCollection $objects)
    {
        foreach ($objects as $object) {
            //$object->setStory(null);
            $this->objects->removeElement($object);
        }
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
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
    
            $this->inputFilter = $inputFilter;
        }
    
        return $this->inputFilter;
    }
}
