<?php

namespace PanierfoyenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adresses
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractAdresses {

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
     */
    private $adressComplete;

    /**
     * @var float
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    private $latitude;

    /**
     * @var float
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    private $longitude;

    /**
     * Set adressComplete
     *
     * @param string $adressComplete
     *
     * @return Lieus
     */
    public function setAdressComplete($adressComplete) {
        $this->adressComplete = $adressComplete;

        return $this;
    }

    /**
     * Generate adressComplete

     * @ORM\PostLoad
     * @return Lieus
     */
    public function generateAdressComplete() {

        $temp = $this->adr1 . ' ' . $this->adr2 . ' ' . $this->adr3 . ' ' . $this->codepostal . ' ' . $this->ville;
//        $temp = $this->codepostal . ' ' . $this->ville;
        $this->setAdressComplete($temp);

        return $this;
    }

    /**
     * Get adressComplete
     *
     * @return string
     */
    public function getAdressComplete() {
        return $this->adressComplete;
    }

    /**
     * Set adr1
     *
     * @param string $adr1
     *
     * @return Lieus
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
     * @return Lieus
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
     * @return Lieus
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
     * Set codepostal
     *
     * @param string $codepostal
     *
     * @return Lieus
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
     * @return Lieus
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
     * Set latitude
     *
     * @param float $latitude
     *
     * @return AbstractAdresses
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return AbstractAdresses
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

}
