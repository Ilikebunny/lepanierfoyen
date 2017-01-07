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

        $myMap = $this->generateMapInfo();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Lieus')->createQueryBuilder('e');
        $lieus = $queryBuilder->getQuery()->getResult();

        return $this->render('PanierfoyenBundle:Default:index.html.twig', array(
                    'myMap' => $myMap,
                    'lieus' => $lieus,
        ));
    }

    private function generateMapInfo() {
        
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('PanierfoyenBundle:Lieus')->createQueryBuilder('e');
        $lieus = $queryBuilder->getQuery()->getResult();

        $queryBuilder = $em->getRepository('PanierfoyenBundle:Producteurs')->createQueryBuilder('e');
        $producteurs = $queryBuilder->getQuery()->getResult();

        //Get center
        if (!empty($lieus)) {
            $myMap['center_lat'] = $lieus[0]->getLatitude();
            $myMap['center_long'] = $lieus[0]->getLongitude();
        } else {
            $temp = $geocoder->geocode('24700 Montpon Menesterol');
            $address = $temp->first();
            $myMap['center_lat'] = $address->getLatitude();
            $myMap['center_long'] = $address->getLongitude();
        }

        //Add markers (lieus)
        foreach ($lieus as $lieu) {
            $myMarker = array();
            $myMarker['latitude'] = $lieu->getLatitude();
            $myMarker['longitude'] = $lieu->getLongitude();
            $myMarker['title'] = $lieu->getLibelle();
            $myMarker['content'] = $lieu->getAdressComplete();
            $myMarker['content2'] = "";
            $myMarker['link'] = "";
            $myMap['markers'][] = $myMarker;

        }

        //Add markers (producteurs)
        foreach ($producteurs as $producteur) {

            $myMarker = array();
            $myMarker['latitude'] = $producteur->getLatitude();
            $myMarker['longitude'] = $producteur->getLongitude();
            $myMarker['title'] = $producteur->getNom();
            $myMarker['content'] = $producteur->getAdressComplete();
            $myMarker['content2'] = "";
            foreach ($producteur->getCategory() as $category) {
                $myMarker['content2'] .= $category->getLibelle() . " ";
            }
            $url = $this->generateUrl(
                    'producteurs_show_slug', array('slug' => $producteur->getSlug()), UrlGeneratorInterface::ABSOLUTE_URL);
            $myMarker['link'] = $url;
            //adding content to make info panel
            $myMap['markers'][] = $myMarker;

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

    /**
     * @Route("/contact-success", name="contact_succes")
     */
    public function showContactSuccessAction() {
        return $this->render('amap/contact-success.html.twig');
    }

}
