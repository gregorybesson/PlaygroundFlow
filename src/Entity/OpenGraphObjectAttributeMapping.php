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
     * if the attribute is an array, it may be an array of objects
     * This 'attributeArray' then represents the attribute of the objects array to get
     * @ORM\ManyToOne(targetEntity="OpenGraphObjectAttribute")
     **/
    protected $attributeArray;
    
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
     * This attribute defines if this OpenGraphObjectAttributeMapping represents the points to add to the leaderboards
     * The best example being the entry->getPoints() method used on the Playground games (quiz) but it could be any numeric attribute
     * @ORM\Column(name="overload_points", type="boolean", nullable=true)
     */
    protected $overloadPoints = 0;
    
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
     * @return the $attributeArray
     */
    public function getAttributeArray()
    {
        return $this->attributeArray;
    }

    /**
     * @param field_type $attributeArray
     */
    public function setAttributeArray($attributeArray)
    {
        $this->attributeArray = $attributeArray;
        
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
     * @return the $overloadPoints
     */
    public function getOverloadPoints()
    {
        return $this->overloadPoints;
    }

    /**
     * @param field_type $overloadPoints
     */
    public function setOverloadPoints($overloadPoints)
    {
        $this->overloadPoints = $overloadPoints;
        
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
                        'name' => 'attributeArray',
                        'required' => false,
                        'allowEmpty' => true,
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'comparison',
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
