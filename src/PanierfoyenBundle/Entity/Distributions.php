<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Distributions
 *
 * @ORM\Table(name="distributions", indexes={@ORM\Index(name="fk_distributions_lieu1_idx", columns={"lieu_id"})})
 * @ORM\Entity
 */
class Distributions {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_distribution", type="date", nullable=false)
     */
    private $dateDistribution;

    /**
     * @var \Lieus
     *
     * @ORM\ManyToOne(targetEntity="Lieus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lieu_id", referencedColumnName="id")
     * })
     */
    private $lieu;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set dateDistribution
     *
     * @param \DateTime $dateDistribution
     *
     * @return Distributions
     */
    public function setDateDistribution($dateDistribution) {
        $this->dateDistribution = $dateDistribution;

        return $this;
    }

    /**
     * Get dateDistribution
     *
     * @return \DateTime
     */
    public function getDateDistribution() {
        return $this->dateDistribution;
    }

    /**
     * Set lieu
     *
     * @param \PanierfoyenBundle\Entity\Lieus $lieu
     *
     * @return Distributions
     */
    public function setLieu(\PanierfoyenBundle\Entity\Lieus $lieu = null) {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return \PanierfoyenBundle\Entity\Lieus
     */
    public function getLieu() {
        return $this->lieu;
    }

}
