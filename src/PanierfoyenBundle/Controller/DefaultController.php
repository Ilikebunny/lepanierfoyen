<?php

namespace PanierfoyenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PanierfoyenBundle\Entity\Lieus;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        if ($temp->count() > 0) {
            $address = $temp->first();
            $myMap['center_lat'] = $address->getLatitude();
            $myMap['center_long'] = $address->getLongitude();
        }

        //Add markers (lieus)
        foreach ($lieus as $lieu) {
            $temp = $geocoder->geocode($lieu->getAdressComplete());
            if ($temp->count() > 0) {
                $address = $temp->first();
                $myMarker = array();
                $myMarker['latitude'] = $address->getLatitude();
                $myMarker['longitude'] = $address->getLongitude();
                $myMarker['title'] = $lieu->getLibelle();
                $myMarker['content'] = $lieu->getAdressComplete();
                $myMarker['content2'] = "";
                $myMarker['link'] = $lieu->getAdressComplete();
                $myMap['markers'][] = $myMarker;
            }
        }

        //Add markers (producteurs)
        foreach ($producteurs as $producteur) {
            $temp = $geocoder->geocode($producteur->getAdressComplete());
            if ($temp->count() > 0) {
                $address = $temp->first();
                $myMarker = array();
                $myMarker['latitude'] = $address->getLatitude();
                $myMarker['longitude'] = $address->getLongitude();
                $myMarker['title'] = $producteur->getNom();
                $myMarker['content'] = $producteur->getAdressComplete();
                $myMarker['content2'] = "";
                foreach ($producteur->getCategory() as $category ){
                    $myMarker['content2'] .= $category->getLibelle() . " ";
                }
                $url = $this->generateUrl(
                        'producteurs_show_slug', array('slug' => $producteur->getSlug()), UrlGeneratorInterface::ABSOLUTE_URL);
                $myMarker['link'] = $url;
                //adding content to make info panel
                $myMap['markers'][] = $myMarker;
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
