<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContratsPayements
 *
 * @ORM\Table(name="contrats_payements", indexes={@ORM\Index(name="fk_contrats_payements_contrats1_idx", columns={"contrat_id"}), @ORM\Index(name="fk_contrats_payements_payements1_idx", columns={"payement_id"})})
 * @ORM\Entity
 */
class ContratsPayements
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
     * @var \Contrats
     *
     * @ORM\ManyToOne(targetEntity="Contrats")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contrat_id", referencedColumnName="id")
     * })
     */
    private $contrat;

    /**
     * @var \Payements
     *
     * @ORM\ManyToOne(targetEntity="Payements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payement_id", referencedColumnName="id")
     * })
     */
    private $payement;



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
     * Set contrat
     *
     * @param \PanierfoyenBundle\Entity\Contrats $contrat
     *
     * @return ContratsPayements
     */
    public function setContrat(\PanierfoyenBundle\Entity\Contrats $contrat = null)
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * Get contrat
     *
     * @return \PanierfoyenBundle\Entity\Contrats
     */
    public function getContrat()
    {
        return $this->contrat;
    }

    /**
     * Set payement
     *
     * @param \PanierfoyenBundle\Entity\Payements $payement
     *
     * @return ContratsPayements
     */
    public function setPayement(\PanierfoyenBundle\Entity\Payements $payement = null)
    {
        $this->payement = $payement;

        return $this;
    }

    /**
     * Get payement
     *
     * @return \PanierfoyenBundle\Entity\Payements
     */
    public function getPayement()
    {
        return $this->payement;
    }
}
