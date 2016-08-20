<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Articles
 *
 * @ORM\Table(name="articles", indexes={@ORM\Index(name="fk_articles_users1_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class Articles {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=90, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenue", type="text", length=65535, nullable=true)
     */
    private $contenue;

    /**
     * @var notification
     * @ORM\Column(name="published", type="boolean")
     */
    protected $published = false;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="change", field="published", value="true")
     * @ORM\Column(name="publication_date", type="datetime", nullable=true)
     */
    private $publicationDate;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="change", field={"titre", "contenue", "tag"})
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var \Users
     * 
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Users", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tags", inversedBy="article", fetch="EAGER")
     * @ORM\JoinTable(name="articles_tags",
     *   joinColumns={
     *     @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *   }
     * )
     */
    private $tag;

    /**
     * Constructor
     */
    public function __construct() {
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * To string
     */
    public function __toString() {
        return $this->titre;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Articles
     */
    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * Set contenue
     *
     * @param string $contenue
     *
     * @return Articles
     */
    public function setContenue($contenue) {
        $this->contenue = $contenue;

        return $this;
    }

    /**
     * Get contenue
     *
     * @return string
     */
    public function getContenue() {
        return $this->contenue;
    }

    /**
     * Set publicationDate
     *
     * @param \DateTime $publicationDate
     *
     * @return Articles
     */
    public function setPublicationDate($publicationDate) {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * Get publicationDate
     *
     * @return \DateTime
     */
    public function getPublicationDate() {
        return $this->publicationDate;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     *
     * @return Articles
     */
    public function setModified($modified) {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified() {
        return $this->modified;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Articles
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Articles
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * Set user
     *
     * @param \PanierfoyenBundle\Entity\Users $user
     *
     * @return Articles
     */
    public function setUser(\PanierfoyenBundle\Entity\Users $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PanierfoyenBundle\Entity\Users
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Add tag
     *
     * @param \PanierfoyenBundle\Entity\Tags $tag
     *
     * @return Articles
     */
    public function addTag(\PanierfoyenBundle\Entity\Tags $tag) {
        $this->tag[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \PanierfoyenBundle\Entity\Tags $tag
     */
    public function removeTag(\PanierfoyenBundle\Entity\Tags $tag) {
        $this->tag->removeElement($tag);
    }

    /**
     * Get tag
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTag() {
        return $this->tag;
    }
    
      /**
     * Set published
     *
     * @param string $published
     *
     * @return Articles
     */
    public function setPublished($published) {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return string
     */
    public function getPublished() {
        return $this->published;
    }

}
