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
 * An Open Graph Object Attribute.
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="opengraph_object_attribute")
 */
class OpenGraphObjectAttribute
{
	protected $inputFilter;
	
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=false)
     */
    protected $code;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenGraphObject")
     */
    protected $object;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $label;
    
    /**
     * values : Boolean
	 *			DateTime
	 *			Float
	 *			Integer
	 *			String
	 *			Array
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $type;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $definition;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $mandatory = 0;

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
	 * @return the $object
	 */
	public function getObject() {
	    
		return $this->object;
	}

	/**
	 * @param field_type $object
	 */
	public function setObject($object) {
	    
		$object->addAttribute($this);
		$this->object = $object;
		
		return $this;
	}

	/**
     *
     * @return the $code
     */
    public function getCode ()
    {
        return $this->code;
    }

    /**
     *
     * @param field_type $code
     */
    public function setCode ($code)
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
	 * @return the $type
	 */
	public function getType() {
	    
		return $this->type;
	}

	/**
	 * @param field_type $type
	 */
	public function setType($type) {
	    
		$this->type = $type;
		
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
    	if (isset($data['code']) && $data['code'] != null) {
    		$this->code = $data['code'];
    	}
    	
    	if (isset($data['label']) && $data['label'] != null) {
    		$this->label = $data['label'];
    	}
    	
    	if (isset($data['definition']) && $data['definition'] != null) {
    		$this->definition = $data['definition'];
    	}
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
