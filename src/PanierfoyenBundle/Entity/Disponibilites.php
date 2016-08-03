<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Disponibilites
 *
 * @ORM\Table(name="disponibilites", indexes={@ORM\Index(name="fk_disponibilites_distributions1_idx", columns={"distributions_id"}), @ORM\Index(name="fk_produits_disponibilites_produits_conditionnements1_idx", columns={"conditionnement_id"})})
 * @ORM\Entity
 */
class Disponibilites
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Distributions
     *
     * @ORM\ManyToOne(targetEntity="Distributions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="distributions_id", referencedColumnName="id")
     * })
     */
    private $distributions;

    /**
     * @var \Conditionnements
     *
     * @ORM\ManyToOne(targetEntity="Conditionnements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="conditionnement_id", referencedColumnName="id")
     * })
     */
    private $conditionnement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Contrats", inversedBy="disponibilite")
     * @ORM\JoinTable(name="contrats_details",
     *   joinColumns={
     *     @ORM\JoinColumn(name="disponibilite_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="contrat_id", referencedColumnName="id")
     *   }
     * )
     */
    private $contrat;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contrat = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set distributions
     *
     * @param \PanierfoyenBundle\Entity\Distributions $distributions
     *
     * @return Disponibilites
     */
    public function setDistributions(\PanierfoyenBundle\Entity\Distributions $distributions = null)
    {
        $this->distributions = $distributions;

        return $this;
    }

    /**
     * Get distributions
     *
     * @return \PanierfoyenBundle\Entity\Distributions
     */
    public function getDistributions()
    {
        return $this->distributions;
    }

    /**
     * Set conditionnement
     *
     * @param \PanierfoyenBundle\Entity\Conditionnements $conditionnement
     *
     * @return Disponibilites
     */
    public function setConditionnement(\PanierfoyenBundle\Entity\Conditionnements $conditionnement = null)
    {
        $this->conditionnement = $conditionnement;

        return $this;
    }

    /**
     * Get conditionnement
     *
     * @return \PanierfoyenBundle\Entity\Conditionnements
     */
    public function getConditionnement()
    {
        return $this->conditionnement;
    }

    /**
     * Add contrat
     *
     * @param \PanierfoyenBundle\Entity\Contrats $contrat
     *
     * @return Disponibilites
     */
    public function addContrat(\PanierfoyenBundle\Entity\Contrats $contrat)
    {
        $this->contrat[] = $contrat;

        return $this;
    }

    /**
     * Remove contrat
     *
     * @param \PanierfoyenBundle\Entity\Contrats $contrat
     */
    public function removeContrat(\PanierfoyenBundle\Entity\Contrats $contrat)
    {
        $this->contrat->removeElement($contrat);
    }

    /**
     * Get contrat
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContrat()
    {
        return $this->contrat;
    }
}
