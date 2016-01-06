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
 * @ORM\Table(name="opengraph_widget")
 */
class OpenGraphWidget
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
    protected $anchor;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $template;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $cssFile;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $jsFile;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $timeout;

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
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param field_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return the $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param field_type $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return the $anchor
     */
    public function getAnchor()
    {
        return $this->anchor;
    }

    /**
     * @param field_type $anchor
     */
    public function setAnchor($anchor)
    {
        $this->anchor = $anchor;
    }

    /**
     * @return the $template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param field_type $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return the $cssFile
     */
    public function getCssFile()
    {
        return $this->cssFile;
    }

    /**
     * @param field_type $cssFile
     */
    public function setCssFile($cssFile)
    {
        $this->cssFile = $cssFile;
    }

    /**
     * @return the $jsFile
     */
    public function getJsFile()
    {
        return $this->jsFile;
    }

    /**
     * @param field_type $jsFile
     */
    public function setJsFile($jsFile)
    {
        $this->jsFile = $jsFile;
    }

    /**
     * @return the $timeout
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param field_type $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
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
