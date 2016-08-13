<?php

namespace PanierfoyenBundle\Controller\front;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;
use PanierfoyenBundle\Entity\Producteurs;
use PanierfoyenBundle\Form\ProducteursType;

/**
 * Producteurs controller.
 *
 * @Route("/producteurs")
 */
class ProducteursController extends Controller {

    /**
     * Lists all Producteurs entities.
     *
     * @Route("/", name="producteurs")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Producteurs')->createQueryBuilder('e');

        list($producteurs, $pagerHtml) = $this->paginator($queryBuilder, $request);

        return $this->render('producteurs/index.html.twig', array(
                    'producteurs' => $producteurs,
                    'pagerHtml' => $pagerHtml,
        ));
    }

    /**
     * Get results from paginator and get paginator view.
     *
     */
    protected function paginator($queryBuilder, $request) {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $request->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me) {
            return $me->generateUrl('producteurs', array('page' => $page));
        };

        // Paginator - view
        $view = new TwitterBootstrap3View();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => 'previous',
            'next_message' => 'next',
        ));

        return array($entities, $pagerHtml);
    }

    /**
     * Finds and displays a Producteurs entity.
     *
     * @Route("/{id}", name="producteurs_show", requirements={"id": "\d+"}) 
     * @Method("GET")
     */
    public function showAction(Producteurs $producteur) {
        return $this->render('producteurs/show.html.twig', array(
                    'producteur' => $producteur
        ));
    }

    /**
     * Finds and displays a Producteurs entity.
     *
     * @Route("/{slug}", name="producteurs_show_slug", requirements={"slug": "[^/]++"}) 
     * @Method("GET")
     */
    public function showActionSlug(Producteurs $producteur) {
        return $this->render('producteurs/show.html.twig', array(
                    'producteur' => $producteur
        ));
    }

    /**
     * Finds and displays a Producteurs entity.
     *
     * @Route("/{slug}/coordinateur", name="producteurs_show_slug_coordinateur", requirements={"slug": "[^/]++"}) 
     * @Method("GET")
     */
    public function showActionSlugCoordinateur(Producteurs $producteur) {
        return $this->render('producteurs/show.coordinateur.html.twig', array(
                    'producteur' => $producteur
        ));
    }

    /**
     * Finds and displays a Producteurs entity.
     *
     * @Route("/{slug}/produits", name="producteurs_show_slug_produits", requirements={"slug": "[^/]++"}) 
     * @Method("GET")
     */
    public function showActionSlugProduits(Producteurs $producteur) {
        return $this->render('producteurs/show.produits.html.twig', array(
                    'producteur' => $producteur
        ));
    }

    /**
     * Finds and displays a Producteurs entity.
     *
     * @Route("/{slug}/photos", name="producteurs_show_slug_photos", requirements={"slug": "[^/]++"}) 
     * @Method("GET")
     */
    public function showActionSlugPhotos(Producteurs $producteur) {
        return $this->render('producteurs/show.photos.html.twig', array(
                    'producteur' => $producteur
        ));
    }

}
