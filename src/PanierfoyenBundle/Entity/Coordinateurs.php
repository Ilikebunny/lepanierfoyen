<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coordinateurs
 *
 * @ORM\Table(name="coordinateurs", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"}), @ORM\UniqueConstraint(name="nom_UNIQUE", columns={"nom"})})
 * @ORM\Entity
 */
class Coordinateurs {

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
     * @ORM\Column(name="nom", type="string", length=90, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="codePostal", type="string", length=5, nullable=true)
     */
    private $codepostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=90, nullable=true)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="adr1", type="string", length=90, nullable=true)
     */
    private $adr1;

    /**
     * @var string
     *
     * @ORM\Column(name="adr2", type="string", length=90, nullable=true)
     */
    private $adr2;

    /**
     * @var string
     *
     * @ORM\Column(name="adr3", type="string", length=90, nullable=true)
     */
    private $adr3;

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
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

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
     * @return Coordinateurs
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
     * Set codepostal
     *
     * @param string $codepostal
     *
     * @return Coordinateurs
     */
    public function setCodepostal($codepostal) {
        $this->codepostal = $codepostal;

        return $this;
    }

    /**
     * Get codepostal
     *
     * @return string
     */
    public function getCodepostal() {
        return $this->codepostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Coordinateurs
     */
    public function setVille($ville) {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille() {
        return $this->ville;
    }

    /**
     * Set adr1
     *
     * @param string $adr1
     *
     * @return Coordinateurs
     */
    public function setAdr1($adr1) {
        $this->adr1 = $adr1;

        return $this;
    }

    /**
     * Get adr1
     *
     * @return string
     */
    public function getAdr1() {
        return $this->adr1;
    }

    /**
     * Set adr2
     *
     * @param string $adr2
     *
     * @return Coordinateurs
     */
    public function setAdr2($adr2) {
        $this->adr2 = $adr2;

        return $this;
    }

    /**
     * Get adr2
     *
     * @return string
     */
    public function getAdr2() {
        return $this->adr2;
    }

    /**
     * Set adr3
     *
     * @param string $adr3
     *
     * @return Coordinateurs
     */
    public function setAdr3($adr3) {
        $this->adr3 = $adr3;

        return $this;
    }

    /**
     * Get adr3
     *
     * @return string
     */
    public function getAdr3() {
        return $this->adr3;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Coordinateurs
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
     * @return Coordinateurs
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
     * Set email
     *
     * @param string $email
     *
     * @return Coordinateurs
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

}
