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
 * @ORM\Table(name="opengraph_object_attribute_mapping")
 */
class OpenGraphObjectAttributeMapping
{
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="OpenGraphObjectMapping")
     **/
    protected $objectMapping;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenGraphObjectAttribute")
     **/
    protected $attribute;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $xpath;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $comparison;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $value;
    
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
     * @return the $objectMapping
     */
    public function getObjectMapping()
    {
        return $this->objectMapping;
    }

    /**
     * @param field_type $objectMapping
     */
    public function setObjectMapping($objectMapping)
    {
        $this->objectMapping = $objectMapping;
        
        return $this;
    }

    /**
     * @return the $attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param field_type $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
        
        return $this;
    }

    /**
     * @return the $xpath
     */
    public function getXpath()
    {
        return $this->xpath;
    }

    /**
     * @param field_type $xpath
     */
    public function setXpath($xpath)
    {
        $this->xpath = $xpath;
        
        return $this;
    }


    /**
     * @return the $comparison
     */
    public function getComparison()
    {
        return $this->comparison;
    }

    /**
     * @param field_type $comparison
     */
    public function setComparison($comparison)
    {
        $this->comparison = $comparison;
        
        return $this;
    }

    /**
     * @return the $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param field_type $value
     */
    public function setValue($value)
    {
        $this->value = $value;
        
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
            
            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'attribute',
                        'required' => false,
                        'allowEmpty' => true,
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'value',
                        'required' => false,
                        'allowEmpty' => true,
                    )
                )
            );
    
            $this->inputFilter = $inputFilter;
        }
    
        return $this->inputFilter;
    }
}
