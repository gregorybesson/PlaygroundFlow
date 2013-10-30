<?php
namespace PlaygroundFlow\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
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
 * @ORM\Table(name="opengraph_story_mapping")
 */
class OpenGraphStoryMapping
{
	protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="OpenGraphStory")
     **/
    protected $story;

    /**
     * @ORM\ManyToOne(targetEntity="OpenGraphDomain")
     **/
    protected $domain;

    /**
     * @ORM\ManyToOne(targetEntity="OpenGraphWebTechno")
     **/
    protected $webTechno;
    
    /**
     * Points associated to this story
     * @ORM\Column(type="integer")
     */
    protected $points;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $picto;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $hint;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $countLimit = 0;
    
    /**
     * @ORM\Column(name="activity_stream_text", type="text", nullable=true)
     */
    protected $activityStreamText;
    
    /**
     * @ORM\OneToMany(targetEntity="OpenGraphObjectMapping", mappedBy="storyMapping")
     */
    protected $objects;
    
    /**
     * @ORM\Column(name="conditions_url", type="string", length=255, nullable=true)
     */
    protected $conditionsUrl;
    
    /**
     * @ORM\Column(name="conditions_xpath", type="string", length=255, nullable=true)
     */
    protected $conditionsXpath;
    
    /**
     * @ORM\Column(name="event_before_url", type="string", length=255, nullable=true)
     */
    protected $eventBeforeUrl;
    
    /**
     * @ORM\Column(name="event_before_xpath", type="string", length=255, nullable=true)
     */
    protected $eventBeforeXpath;
    
    /**
     * @ORM\Column(name="event_after_url", type="string", length=255, nullable=true)
     */
    protected $eventAfterUrl;
    
    /**
     * @ORM\Column(name="event_after_xpath", type="string", length=255, nullable=true)
     */
    protected $eventAfterXpath;
    
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
    	$this->properties = new ArrayCollection();
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
    }

    /**
	 * @return the $story
	 */
	public function getStory() {
		return $this->story;
	}

	/**
	 * @param field_type $story
	 */
	public function setStory($story) {
		$this->story = $story;
	}

	/**
	 * @return the $domain
	 */
	public function getDomain() {
		return $this->domain;
	}

	/**
	 * @param field_type $domain
	 */
	public function setDomain($domain) {
		$domain->addStoryMapping($this);
		$this->domain = $domain;
	}

    /**
     * @return the $webTechno
     */
    public function getWebTechno() {
        return $this->webTechno;
    }

    /**
     * @param field_type $webTechno
     */
    public function setWebTechno($webTechno) {
        $webTechno->addStoryMapping($this);
        $this->webTechno = $webTechno;
    }

	/**
     * @return the $points
     */
    public function getPoints()
    {
        return $this->points;
    }

	/**
     * @param field_type $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

	/**
     * @return the $picto
     */
    public function getPicto()
    {
        return $this->picto;
    }

	/**
     * @param field_type $picto
     */
    public function setPicto($picto)
    {
        $this->picto = $picto;
    }

	/**
     * @return the $hint
     */
    public function getHint()
    {
        return $this->hint;
    }

	/**
     * @param field_type $hint
     */
    public function setHint($hint)
    {
        $this->hint = $hint;
    }

	/**
     * @return the $activityStreamText
     */
    public function getActivityStreamText()
    {
        return $this->activityStreamText;
    }

	/**
     * @param field_type $activityStreamText
     */
    public function setActivityStreamText($activityStreamText)
    {
        $this->activityStreamText = $activityStreamText;
    }

	/**
     * @return the $countLimit
     */
    public function getCountLimit()
    {
        return $this->countLimit;
    }

	/**
     * @param number $countLimit
     */
    public function setCountLimit($countLimit)
    {
        $this->countLimit = $countLimit;
    }

	public function addObject($object)
	{
		$this->objects[] = $object;
	}
	
	/**
	 * @return the $attributes
	 */
	public function getObjects() {
		return $this->objects;
	}

	/**
	 * @param field_type $attributes
	 */
	public function setObjects($objects) {
		$this->objects = $objects;
	}

	/**
	 * @return the $conditionsUrl
	 */
	public function getConditionsUrl() {
		return $this->conditionsUrl;
	}

	/**
	 * @param field_type $conditionsUrl
	 */
	public function setConditionsUrl($conditionsUrl) {
		$this->conditionsUrl = $conditionsUrl;
	}

	/**
	 * @return the $conditionsXpath
	 */
	public function getConditionsXpath() {
		return $this->conditionsXpath;
	}

	/**
	 * @param field_type $conditionsXpath
	 */
	public function setConditionsXpath($conditionsXpath) {
		$this->conditionsXpath = $conditionsXpath;
	}

	/**
	 * @return the $eventBeforeUrl
	 */
	public function getEventBeforeUrl() {
		return $this->eventBeforeUrl;
	}

	/**
	 * @param field_type $eventBeforeUrl
	 */
	public function setEventBeforeUrl($eventBeforeUrl) {
		$this->eventBeforeUrl = $eventBeforeUrl;
	}

	/**
	 * @return the $eventBeforeXpath
	 */
	public function getEventBeforeXpath() {
		return $this->eventBeforeXpath;
	}

	/**
	 * @param field_type $eventBeforeXpath
	 */
	public function setEventBeforeXpath($eventBeforeXpath) {
		$this->eventBeforeXpath = $eventBeforeXpath;
	}

	/**
	 * @return the $eventAfterUrl
	 */
	public function getEventAfterUrl() {
		return $this->eventAfterUrl;
	}

	/**
	 * @param field_type $eventAfterUrl
	 */
	public function setEventAfterUrl($eventAfterUrl) {
		$this->eventAfterUrl = $eventAfterUrl;
	}

	/**
	 * @return the $eventAfterXpath
	 */
	public function getEventAfterXpath() {
		return $this->eventAfterXpath;
	}

	/**
	 * @param field_type $eventAfterXpath
	 */
	public function setEventAfterXpath($eventAfterXpath) {
		$this->eventAfterXpath = $eventAfterXpath;
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
