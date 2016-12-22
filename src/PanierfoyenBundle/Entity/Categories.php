<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 *
 * @ORM\Table(name="categories", uniqueConstraints={@ORM\UniqueConstraint(name="libelle_UNIQUE", columns={"libelle"})})
 * @ORM\Entity(repositoryClass="PanierfoyenBundle\Repository\CategoriesRepository")
 */
class Categories {

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
     * @ORM\Column(name="libelle", type="string", length=90, nullable=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=250, nullable=true)
     */
    private $logo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Producteurs", mappedBy="category")
     */
    private $producteur;

    /**
     * Constructor
     */
    public function __construct() {
        $this->producteur = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Categories
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
     * Set logo
     *
     * @param string $logo
     *
     * @return Categories
     */
    public function setLogo($logo) {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * Add producteur
     *
     * @param \PanierfoyenBundle\Entity\Producteurs $producteur
     *
     * @return Categories
     */
    public function addProducteur(\PanierfoyenBundle\Entity\Producteurs $producteur) {
        $this->producteur[] = $producteur;

        return $this;
    }

    /**
     * Remove producteur
     *
     * @param \PanierfoyenBundle\Entity\Producteurs $producteur
     */
    public function removeProducteur(\PanierfoyenBundle\Entity\Producteurs $producteur) {
        $this->producteur->removeElement($producteur);
    }

    /**
     * Get producteur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducteur() {
        return $this->producteur;
    }

}
