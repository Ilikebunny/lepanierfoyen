<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PanierfoyenBundle\Entity\AbstractAdresses;

/**
 * Lieus
 *
 * @ORM\Table(name="lieus")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Lieus extends AbstractAdresses{

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
     * @ORM\Column(name="libelle", type="string", length=45, nullable=false)
     */
    private $libelle;

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
     * @return Lieus
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

}
