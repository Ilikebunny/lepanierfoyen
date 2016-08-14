<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conditionnements
 *
 * @ORM\Table(name="conditionnements", indexes={@ORM\Index(name="fk_produits_conditionnements_produits_idx", columns={"produit_id"})})
 * @ORM\Entity
 */
class Conditionnements
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=50, nullable=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="prix_unitaire", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $prixUnitaire;

    /**
     * @var \Produits
     *
     * @ORM\ManyToOne(targetEntity="Produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     * })
     */
    private $produit;

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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Conditionnements
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set prixUnitaire
     *
     * @param string $prixUnitaire
     *
     * @return Conditionnements
     */
    public function setPrixUnitaire($prixUnitaire)
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    /**
     * Get prixUnitaire
     *
     * @return string
     */
    public function getPrixUnitaire()
    {
        return $this->prixUnitaire;
    }

    /**
     * Set produit
     *
     * @param \PanierfoyenBundle\Entity\Produits $produit
     *
     * @return Conditionnements
     */
    public function setProduit(\PanierfoyenBundle\Entity\Produits $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \PanierfoyenBundle\Entity\Produits
     */
    public function getProduit()
    {
        return $this->produit;
    }
}
