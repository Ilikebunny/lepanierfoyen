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
use PanierfoyenBundle\Entity\Coordinateurs;
use PanierfoyenBundle\Form\CoordinateursType;

/**
 * Coordinateurs controller.
 *
 * @Route("/coordinateurs")
 */
class CoordinateursController extends Controller {

    /**
     * Lists all Coordinateurs entities.
     *
     * @Route("/", name="coordinateurs")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Coordinateurs')->createQueryBuilder('e');

        list($coordinateurs, $pagerHtml) = $this->paginator($queryBuilder, $request);

        return $this->render('coordinateurs/index.html.twig', array(
                    'coordinateurs' => $coordinateurs,
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
            return $me->generateUrl('coordinateurs', array('page' => $page));
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
     * Finds and displays a Coordinateurs entity.
     *
     * @Route("/{id}", name="coordinateurs_show")
     * @Method("GET")
     */
    public function showAction(Coordinateurs $coordinateur) {
        return $this->render('coordinateurs/show.html.twig', array(
                    'coordinateur' => $coordinateur
        ));
    }

}
