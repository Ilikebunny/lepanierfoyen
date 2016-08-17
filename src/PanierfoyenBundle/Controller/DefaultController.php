<?php

namespace PanierfoyenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller {

    /**
     * @Route("/", name="_welcome")
     */
    public function indexAction() {
        return $this->render('PanierfoyenBundle:Default:index.html.twig');
    }

    /**
     * @Route("/le-concept", name="amap_concept")
     */
    public function leConceptAction() {
        return $this->render('amap/le-concept.html.twig');
    }

}
