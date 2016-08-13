<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Frequences
 *
 * @ORM\Table(name="frequences", uniqueConstraints={@ORM\UniqueConstraint(name="libelle_UNIQUE", columns={"libelle"})})
 * @ORM\Entity
 */
class Frequences {

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
     * @ORM\Column(name="libelle", type="string", length=90, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="string", length=90, nullable=false)
     */
    private $details;

    /**
     * @var integer
     *
     * @ORM\Column(name="jours", type="integer", nullable=true)
     */
    private $jours;

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
     * @return Frequences
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
     * Set libelle
     *
     * @param string $details
     *
     * @return Frequences
     */
    public function setDetails($details) {
        $this->details = $details;

        return $this;
    }

    /**
     * Get $details
     *
     * @return string
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * Set jours
     *
     * @param integer $jours
     *
     * @return Frequences
     */
    public function setJours($jours) {
        $this->jours = $jours;

        return $this;
    }

    /**
     * Get jours
     *
     * @return integer
     */
    public function getJours() {
        return $this->jours;
    }

}
