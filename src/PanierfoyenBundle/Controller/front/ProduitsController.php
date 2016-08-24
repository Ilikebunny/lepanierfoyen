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
use PanierfoyenBundle\Entity\Produits;

/**
 * Produits controller.
 *
 * @Route("/produits")
 */
class ProduitsController extends Controller {

    /**
     * Lists all Produits entities.
     *
     * @Route("/", name="produits")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Produits')->createQueryBuilder('e');

//        list($produits, $pagerHtml) = $this->paginator($queryBuilder, $request);
        $paginator = $this->container->get('panierfoyen.paginator');
        list($produits, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 8, 'produits');
        
        return $this->render('produits/index.html.twig', array(
                    'produits' => $produits,
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
            return $me->generateUrl('produits', array('page' => $page));
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
     * Finds and displays a Produits entity.
     *
     * @Route("/{id}", name="produits_show")
     * @Method("GET")
     */
    public function showAction(Produits $produit) {
        $deleteForm = $this->createDeleteForm($produit);
        return $this->render('produits/show.html.twig', array(
                    'produit' => $produit,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

}
