<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrats
 *
 * @ORM\Table(name="contrats", indexes={@ORM\Index(name="fk_contrats_users1_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class Contrats
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date", nullable=true)
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=true)
     */
    private $datefin;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Disponibilites", mappedBy="contrat")
     */
    private $disponibilite;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->disponibilite = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Contrats
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set datefin
     *
     * @param \DateTime $datefin
     *
     * @return Contrats
     */
    public function setDatefin($datefin)
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * Get datefin
     *
     * @return \DateTime
     */
    public function getDatefin()
    {
        return $this->datefin;
    }

    /**
     * Set user
     *
     * @param \PanierfoyenBundle\Entity\Users $user
     *
     * @return Contrats
     */
    public function setUser(\PanierfoyenBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PanierfoyenBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add disponibilite
     *
     * @param \PanierfoyenBundle\Entity\Disponibilites $disponibilite
     *
     * @return Contrats
     */
    public function addDisponibilite(\PanierfoyenBundle\Entity\Disponibilites $disponibilite)
    {
        $this->disponibilite[] = $disponibilite;

        return $this;
    }

    /**
     * Remove disponibilite
     *
     * @param \PanierfoyenBundle\Entity\Disponibilites $disponibilite
     */
    public function removeDisponibilite(\PanierfoyenBundle\Entity\Disponibilites $disponibilite)
    {
        $this->disponibilite->removeElement($disponibilite);
    }

    /**
     * Get disponibilite
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }
}
