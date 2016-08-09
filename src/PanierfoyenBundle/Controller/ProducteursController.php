<?php

namespace PanierfoyenBundle\Controller;

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
     * Creates a form to delete a Producteurs entity.
     *
     * @param Producteurs $producteur The Producteurs entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Producteurs $producteur) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('producteurs_delete', array('id' => $producteur->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Finds and displays a Producteurs entity.
     *
     * @Route("/{id}", name="producteurs_show")
     * @Method("GET")
     */
    public function showAction(Producteurs $producteur) {
        $deleteForm = $this->createDeleteForm($producteur);
        return $this->render('producteurs/show.html.twig', array(
                    'producteur' => $producteur,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

}
