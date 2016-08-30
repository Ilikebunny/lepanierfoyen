<?php

namespace PanierfoyenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller {

    /**
     * @Route("/", name="_welcome")
     */
    public function indexAction() {
        $result = $this->container
                ->get('bazinga_geocoder.geocoder')
                ->geocode('7 avenue Jean Moulin 24700 Montpon Menesterol');

        return $this->render('PanierfoyenBundle:Default:index.html.twig', array(
                    'test' => $result,
        ));
    }

    /**
     * @Route("/le-concept", name="amap_concept")
     */
    public function leConceptAction() {
        return $this->render('amap/le-concept.html.twig');
    }

    /**
     * @Route("/calendrier", name="amap_calendrier")
     */
    public function showCalendrierAction() {
        return $this->render('amap/calendrier.html.twig');
    }

    /**
     * @Route("/cartographie", name="amap_cartographie")
     */
    public function showCarteAction() {
        return $this->render('amap/cartographie.html.twig');
    }

}
