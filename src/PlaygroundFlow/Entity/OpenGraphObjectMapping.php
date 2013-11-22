<?php
namespace PlaygroundFlow\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * An entry represent a game session.
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="opengraph_object_mapping")
 */
class OpenGraphObjectMapping
{
	protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenGraphStoryMapping", inversedBy="objects")
     * @ORM\JoinColumn(name="storyMapping_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $storyMapping;

    /**
     * @ORM\ManyToOne(targetEntity="OpenGraphObject")
     **/
    protected $object;
    
    /**
     * @ORM\OneToMany(targetEntity="OpenGraphObjectAttributeMapping", mappedBy="objectMapping", cascade={"persist","remove"})
     */
    protected $attributes;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $xpath;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /** @PrePersist */
    public function createChrono()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
    }

    /** @PreUpdate */
    public function updateChrono()
    {
        $this->updated_at = new \DateTime("now");
    }

    /**
     *
     * @return the $id
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     *
     * @param field_type $id
     */
    public function setId ($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
	 * @return the $storyMapping
	 */
	public function getStoryMapping() {
	    
		return $this->storyMapping;
	}

	/**
	 * @param field_type $storyMapping
	 */
	public function setStoryMapping($storyMapping) {
	    
		$storyMapping->addObject($this);
		$this->storyMapping = $storyMapping;
		
		return $this;
	}

	/**
	 * @return the $object
	 */
	public function getObject() {
	    
		return $this->object;
	}

	/**
	 * @param field_type $object
	 */
	public function setObject($object) {
	    
		$this->object = $object;
		
		return $this;
	}
	

	public function addAttribute($attribute)
	{
	    $this->attributes[] = $attribute;
	}
	
	/**
	 * @return the $attributes
	 */
	public function getAttributes() {
	    
	    return $this->attributes;
	}
	
	/**
	 * @param field_type $attributes
	 */
	public function setAttributes($attributes) {
	    
	    $this->attributes = $attributes;
	    
	    return $this;
	}
	
	public function addAttributes(\Doctrine\Common\Collections\ArrayCollection $attributes)
	{
	    foreach ($attributes as $attribute) {
	        $attribute->setObjectMapping($this);
	        $this->attributes->add($attribute);
	    }
	}
	
	public function removeAttributes(\Doctrine\Common\Collections\ArrayCollection $attributes)
	{
	    foreach ($attributes as $attribute) {
	        $attribute->setObjectMapping(null);
	        $this->attributes->removeElement($attribute);
	    }
	}

	public function clearAttributes()
	{
	    foreach ($this->getAttributes() as $attribute) {
	        $attribute->setObjectMapping(null);
	        $this->attributes->removeElement($attribute);
	    }
	}

	/**
	 * @return the $xpath
	 */
	public function getXpath() {
	    
		return $this->xpath;
	}

	/**
	 * @param field_type $xpath
	 */
	public function setXpath($xpath) {
	    
		$this->xpath = $xpath;
		
		return $this;
	}

	/**
     * @return the $created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        
        return $this;
    }

    /**
     * @return the $updated_at
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param \DateTime $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        
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
