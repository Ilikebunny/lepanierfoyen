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
    public function indexAction() {
        
        $em = $this->getDoctrine()->getManager();

        $queryBuilder2 = $em->getRepository('PanierfoyenBundle:Categories')
                ->getAllWithProduitsAndVariations()
                ->getResult();

        $categories = $em->getRepository('PanierfoyenBundle:Categories')
                ->findAll();

        return $this->render('produits/index.html.twig', array(
                    'categories' => $categories,
                    'categories2' => $queryBuilder2,
        ));
    }

    /**
     * Lists all Produits entities filtered by categories
     *
     * @Route("/categories/{categorySelected}", name="produitsByCategories")
     * @Method("GET")
     */
    public function indexCategorieAction($categorySelected) {

        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('PanierfoyenBundle:Categories')
                ->findAll();

        $queryBuilder2 = $em->getRepository('PanierfoyenBundle:Categories')
                ->getAllWithProduitsAndVariationsFiltered($categorySelected)
                ->getResult();

        return $this->render('produits/index.html.twig', array(
                    'categories' => $categories,
                    'categories2' => $queryBuilder2,
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
