<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produits
 *
 * @ORM\Table(name="produits", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_produits_producteurs1_idx", columns={"producteur_id"}), @ORM\Index(name="fk_produits_frequences1_idx", columns={"frequence_id"}), @ORM\Index(name="fk_produits_categories_production1_idx", columns={"category_id"})})
 * @ORM\Entity(repositoryClass="PanierfoyenBundle\Repository\ProduitsRepository")
 */
class Produits {

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
     * @ORM\Column(name="libelle", type="string", length=50, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptif", type="string", length=500, nullable=true)
     */
    private $descriptif;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=250, nullable=true)
     */
    private $image;

    /**
     * @var \Categories
     *
     * @ORM\ManyToOne(targetEntity="Categories", fetch="EAGER", inversedBy="produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \Frequences
     *
     * @ORM\ManyToOne(targetEntity="Frequences", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="frequence_id", referencedColumnName="id")
     * })
     */
    private $frequence;

    /**
     * @var \Producteurs
     *
     * @ORM\ManyToOne(targetEntity="Producteurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="producteur_id", referencedColumnName="id")
     * })
     */
    private $producteur;

    /**
     * @ORM\OneToMany(targetEntity="Conditionnements", mappedBy="produit", fetch="EAGER")
     */
    protected $les_conditionnements;

    public function __construct() {
        $this->les_conditionnements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get les_conditionnements
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLes_conditionnements() {
        return $this->les_conditionnements;
    }

    /**
     * To string
     */
    public function __toString() {
        return $this->libelle;
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Produits
     */
    public function setLibelle($libelle) {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle() {
        return $this->libelle;
    }

    /**
     * Set descriptif
     *
     * @param string $descriptif
     *
     * @return Produits
     */
    public function setDescriptif($descriptif) {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * Get descriptif
     *
     * @return string
     */
    public function getDescriptif() {
        return $this->descriptif;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Produits
     */
    public function setImage($image) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set category
     *
     * @param \PanierfoyenBundle\Entity\Categories $category
     *
     * @return Produits
     */
    public function setCategory(\PanierfoyenBundle\Entity\Categories $category = null) {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \PanierfoyenBundle\Entity\Categories
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Set frequence
     *
     * @param \PanierfoyenBundle\Entity\Frequences $frequence
     *
     * @return Produits
     */
    public function setFrequence(\PanierfoyenBundle\Entity\Frequences $frequence = null) {
        $this->frequence = $frequence;

        return $this;
    }

    /**
     * Get frequence
     *
     * @return \PanierfoyenBundle\Entity\Frequences
     */
    public function getFrequence() {
        return $this->frequence;
    }

    /**
     * Set producteur
     *
     * @param \PanierfoyenBundle\Entity\Producteurs $producteur
     *
     * @return Produits
     */
    public function setProducteur(\PanierfoyenBundle\Entity\Producteurs $producteur = null) {
        $this->producteur = $producteur;

        return $this;
    }

    /**
     * Get producteur
     *
     * @return \PanierfoyenBundle\Entity\Producteurs
     */
    public function getProducteur() {
        return $this->producteur;
    }

}
