<?php

namespace PanierfoyenBundle\Controller\front;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
        
        //test
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Produits')->getAllOrderedByCategory();

        //TEST2
        $qb = $em->getRepository('PanierfoyenBundle:Categories')->getAllWithProduits();
        print_r($qb->getResult());
        
//        Pagination
        $paginator = $this->container->get('panierfoyen.paginator');
        list($produits, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 12, 'produits');

        return $this->render('produits/index.html.twig', array(
                    'produits' => $produits,
                    'pagerHtml' => $pagerHtml,
        ));
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
