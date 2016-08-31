<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use PanierfoyenBundle\Entity\AbstractAdresses;

/**
 * Producteurs
 *
 * @ORM\Table(name="producteurs", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"}), @ORM\UniqueConstraint(name="nom_UNIQUE", columns={"nom"})}, indexes={@ORM\Index(name="fk_producteurs_coordinateurs1_idx", columns={"coordinateur_id"})})
 * @ORM\Entity(repositoryClass="PanierfoyenBundle\Repository\ProducteursRepository")
 */
class Producteurs extends AbstractAdresses{

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
     * @ORM\Column(name="nom", type="string", length=90, nullable=false, unique=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="presentation", type="text", length=65535, nullable=true)
     */
    private $presentation;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=20, nullable=true)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=20, nullable=true)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="site_internet", type="string", length=100, nullable=true)
     */
    private $siteInternet;

    /**
     * @var string
     *
     * @ORM\Column(name="cheque_ordre", type="string", length=100, nullable=true)
     */
    private $chequeOrdre;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var \Coordinateurs
     *
     * @ORM\ManyToOne(targetEntity="Coordinateurs", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="coordinateur_id", referencedColumnName="id")
     * })
     */
    private $coordinateur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Categories", inversedBy="producteur", fetch="EAGER")
     * @ORM\JoinTable(name="categories_producteurs",
     *   joinColumns={
     *     @ORM\JoinColumn(name="producteur_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *   }
     * )
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="Gallery", mappedBy="producteur", fetch="EAGER")
     */
    protected $myGallery;

    /**
     * Get myGallery
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMyGallery() {
        return $this->myGallery;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
         $this->les_conditionnements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * To string
     */
    public function __toString() {
        return $this->nom;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Producteurs
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set presentation
     *
     * @param string $presentation
     *
     * @return Producteurs
     */
    public function setPresentation($presentation) {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string
     */
    public function getPresentation() {
        return $this->presentation;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Producteurs
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getslug() {
        return $this->slug;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Producteurs
     */
    public function setTel($tel) {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel() {
        return $this->tel;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Producteurs
     */
    public function setMobile($mobile) {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile() {
        return $this->mobile;
    }

    /**
     * Set siteInternet
     *
     * @param string $siteInternet
     *
     * @return Producteurs
     */
    public function setSiteInternet($siteInternet) {
        $this->siteInternet = $siteInternet;

        return $this;
    }

    /**
     * Get siteInternet
     *
     * @return string
     */
    public function getSiteInternet() {
        return $this->siteInternet;
    }

    /**
     * Set chequeOrdre
     *
     * @param string $chequeOrdre
     *
     * @return Producteurs
     */
    public function setChequeOrdre($chequeOrdre) {
        $this->chequeOrdre = $chequeOrdre;

        return $this;
    }

    /**
     * Get chequeOrdre
     *
     * @return string
     */
    public function getChequeOrdre() {
        return $this->chequeOrdre;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Producteurs
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set coordinateur
     *
     * @param \PanierfoyenBundle\Entity\Coordinateurs $coordinateur
     *
     * @return Producteurs
     */
    public function setCoordinateur(\PanierfoyenBundle\Entity\Coordinateurs $coordinateur = null) {
        $this->coordinateur = $coordinateur;

        return $this;
    }

    /**
     * Get coordinateur
     *
     * @return \PanierfoyenBundle\Entity\Coordinateurs
     */
    public function getCoordinateur() {
        return $this->coordinateur;
    }

    /**
     * Add category
     *
     * @param \PanierfoyenBundle\Entity\Categories $category
     *
     * @return Producteurs
     */
    public function addCategory(\PanierfoyenBundle\Entity\Categories $category) {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \PanierfoyenBundle\Entity\Categories $category
     */
    public function removeCategory(\PanierfoyenBundle\Entity\Categories $category) {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory() {
        return $this->category;
    }

}
