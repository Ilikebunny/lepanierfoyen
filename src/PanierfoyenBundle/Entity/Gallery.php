<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gallery
 *
 * @ORM\Table(name="gallery", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class Gallery {

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
     * @ORM\Column(name="image", type="string", length=250, nullable=true)
     */
    private $image;

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
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
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
