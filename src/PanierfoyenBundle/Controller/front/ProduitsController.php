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

        //test
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Produits')->getAllOrderedByCategory();

        //TEST2
        $queryBuilder2 = $em->getRepository('PanierfoyenBundle:Categories')->getAllWithProduitsAndVariations();
        $queryBuilder2 = $queryBuilder2->getResult();

        $categories = $em->getRepository('PanierfoyenBundle:Categories')->findAll();

//        Pagination
        $paginator = $this->container->get('panierfoyen.paginator');
        list($produits, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 12, 'produits');

        return $this->render('produits/index.html.twig', array(
                    'produits' => $produits,
                    'categories' => $categories,
                    'pagerHtml' => $pagerHtml,
                    'categories2' => $queryBuilder2,
        ));
    }

    /**
     * Lists all Produits entities filtered by categories
     *
     * @Route("/categories/{categorySelected}", name="produitsByCategories")
     * @Method("GET")
     */
    public function indexCategorieAction(Request $request, $categorySelected) {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('PanierfoyenBundle:Categories')->findAll();
        $categorySelectedObject = $em->getRepository('PanierfoyenBundle:Categories')->findOneByLibelle($categorySelected);

        //temp
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Producteurs')->createQueryBuilder('a')
                ->select('a')
                ->leftJoin('a.category', 'c')
                ->addSelect('c');

        $queryBuilder = $queryBuilder->add('where', $queryBuilder->expr()->in('c', ':c'))
                ->setParameter('c', $categorySelectedObject->getId())
        ;

        $queryBuilder2 = $em->getRepository('PanierfoyenBundle:Categories')->getAllWithProduitsAndVariationsFiltered($categorySelected)->getResult();

        //temp
        $routeName = 'producteursByCategories';

        $paginator = $this->container->get('panierfoyen.paginator');
        list($producteurs, $pagerHtml) = $paginator->paginatorWithParameters($queryBuilder, $request, 8, $routeName, 'categorySelected', $categorySelected);

//        list($producteurs, $pagerHtml) = $this->paginatorByCategory($queryBuilder, $request, 8, $routeName, 'categorySelected', $categorySelected);

        return $this->render('produits/index.html.twig', array(
                    'producteurs' => $producteurs,
                    'categories' => $categories,
                    'categorySelected' => $categorySelected,
                    'categorySelectedObject' => $categorySelectedObject,
//                    'pagerHtml' => $pagerHtml,
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
