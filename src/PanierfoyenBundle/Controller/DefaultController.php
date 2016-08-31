<?php

namespace PanierfoyenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PanierfoyenBundle\Entity\Lieus;

class DefaultController extends Controller {

    /**
     * @Route("/", name="_welcome")
     */
    public function indexAction() {
        $result = $this->container
                ->get('bazinga_geocoder.geocoder')
                ->geocode('7 avenue Jean Moulin 24700 Montpon Menesterol');

        $myMap = $this->generateMapInfo();

        return $this->render('PanierfoyenBundle:Default:index.html.twig', array(
                    'test' => $result,
                    'myMap' => $myMap,
        ));
    }

    private function generateMapInfo() {
        $geocoder = $result = $this->container->get('bazinga_geocoder.geocoder');

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('PanierfoyenBundle:Lieus')->createQueryBuilder('e');
        $lieus = $queryBuilder->getQuery()->getResult();

        $queryBuilder = $em->getRepository('PanierfoyenBundle:Producteurs')->createQueryBuilder('e');
        $producteurs = $queryBuilder->getQuery()->getResult();

        //Get center
        $temp = $geocoder->geocode($lieus[0]->getAdressComplete());
        $address = $temp->first();
        $myMap['center_lat'] = $address->getLatitude();
        $myMap['center_long'] = $address->getLongitude();

        //Add markers (lieus)
        foreach ($lieus as $lieu) {
            $temp = $geocoder->geocode($lieu->getAdressComplete());
            $address = $temp->first();
            $myMarker = array();
            $myMarker['latitude'] = $address->getLatitude();
            $myMarker['longitude'] = $address->getLongitude();
            $myMap['markers'][] = $myMarker;
        }

        //Add markers (producteurs)
        foreach ($producteurs as $producteur) {
            $temp = $geocoder->geocode($producteur->getAdressComplete());
            if ($temp->count() > 0) {
                $address = $temp->first();
                if (count($address) > 0) {
                    $myMarker = array();
                    $myMarker['latitude'] = $address->getLatitude();
                    $myMarker['longitude'] = $address->getLongitude();
                    $myMap['markers'][] = $myMarker;
                }
            }
        }

        return $myMap;
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
