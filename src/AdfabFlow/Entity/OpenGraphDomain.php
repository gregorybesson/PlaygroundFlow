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
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="opengraph_domain")
 */
class OpenGraphDomain
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
    protected $title;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $domain;

    /**
     * @ORM\ManyToMany(targetEntity="PlaygroundFlow\Entity\OpenGraphApplication", inversedBy="domains")
     * @ORM\JoinTable(name="opengraph_application_domain")
     */
    protected $apps;
    
    /**
     * @ORM\OneToMany(targetEntity="OpenGraphStoryMapping", mappedBy="domain")
     */
    protected $storyMappings;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

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
    	$this->apps = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->storyMappings = new \Doctrine\Common\Collections\ArrayCollection();
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
    }
    
    /**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param field_type $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return the $apps
	 */
	public function getApps() {
		return $this->apps;
	}

	/**
	 * @param field_type $apps
	 */
	public function setApps($apps) {
		$this->apps = $apps;
	}
	
	public function addApp($app)
	{
		$this->apps[] = $app;
	}

	/**
     *
     * @return the $domain
     */
    public function getDomain ()
    {
        return $this->domain;
    }

    /**
     *
     * @param field_type $domain
     */
    public function setDomain ($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return the $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param field_type $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return the $storyMapping
     */
    public function addStoryMapping($storyMapping) {
    	$this->storyMappings[] = $storyMapping;
    }
    
    /**
	 * @return the $storyMappings
	 */
	public function getStoryMappings() {
		return $this->storyMappings;
	}

	/**
	 * @param field_type $storyMappings
	 */
	public function setStoryMappings($storyMappings) {
		$this->storyMappings = $storyMappings;
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
