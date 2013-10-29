<?php
namespace PlaygroundFlow\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="opengraph_story_telling",indexes={@ORM\Index(name="idx_opengraph_story_mapping_id", columns={"opengraph_story_mapping_id"})})
 */
class OpenGraphStoryTelling
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="OpenGraphStoryMapping")
     * @ORM\JoinColumn(name="opengraph_story_mapping_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $openGraphStoryMapping;

    /**
     * @ORM\ManyToOne(targetEntity="PlaygroundUser\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE")
     **/
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="PlaygroundUser\Entity\Prospect")
     * @ORM\JoinColumn(name="prospect_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $prospect;

    /**
     * The jsonified object associated to the event
     * @ORM\Column(type="text", nullable=true)
     */
    protected $object;

    /**
     * Une clé cryptée permettant d'identifier l'event
     * @ORM\Column(name="secret_key", type="string", length=255, nullable=true)
     */
    protected $secretKey;
    
    /**
     * Points associated to this story
     * @ORM\Column(type="integer")
     */
    protected $points;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

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
     * @param $id
     * @return Block|mixed
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


    /**
     * @return the $prospect
     */
    public function getProspect()
    {
        return $this->prospect;
    }

    /**
     * @param Prospect $prospect
     */
    public function setProspect($prospect)
    {
        $this->prospect = $prospect;
    }

    /**
     * @return the $openGraphStoryMapping
     */
    public function getOpenGraphStoryMapping()
    {
        return $this->openGraphStoryMapping;
    }

    /**
     * @param field_type $openGraphStoryMapping
     */
    public function setOpenGraphStoryMapping($openGraphStoryMapping)
    {
        $this->openGraphStoryMapping = $openGraphStoryMapping;
    }
    
    /**
     * @return the $object
     */
    public function getObject()
    {
        return $this->object;
    }
    
    /**
     * @param field_type $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return the $secretKey
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param field_type $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
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
     *
     * @return the $createdAt
     */
    public function getCreatedAt ()
    {
        return $this->createdAt;
    }

    /**
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt ($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     *
     * @return the $updatedAt
     */
    public function getUpdatedAt ()
    {
        return $this->updatedAt;
    }

    /**
     *
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt ($updatedAt)
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
        /*$this->id = $data['id'];
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->displayName = $data['displayName'];
        $this->password = $data['password'];
        $this->state = $data['state'];*/
    }
}
