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
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Producteurs')->getAllOrderedByCategoryAndName();

        // $_GET parameters
        $pageNumber = $request->query->get('page');

        //Variables
        $maxResults = 13;
        $offset = ($pageNumber - 1) * $maxResults;
        if ($offset < 0)
            $offset = 0;

        //TEST
        $queryBuilder2 = $em->getRepository('PanierfoyenBundle:Categories')->getAllWithProducteursAndProduits($offset, $maxResults);


        $paginator = $this->container->get('panierfoyen.paginator');
        $routeName = 'producteurs';
        list($producteurs, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, $maxResults, $routeName);

        $queryBuilder2 = $queryBuilder2->getResult();

//        list($producteurs, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $categories = $em->getRepository('PanierfoyenBundle:Categories')->findAll();



        return $this->render('producteurs/index.html.twig', array(
                    'producteurs' => $producteurs,
                    'categories' => $categories,
                    'pagerHtml' => $pagerHtml,
                    'categories2' => $queryBuilder2,
                    'pageNumber' => $pageNumber,
                    'offset' => $offset,
        ));
    }

    /**
     * Lists all Producteurs entities filtered by categories
     *
     * @Route("/categories/{categorySelected}", name="producteursByCategories")
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
        //temp
        $routeName = 'producteursByCategories';

        $paginator = $this->container->get('panierfoyen.paginator');
        list($producteurs, $pagerHtml) = $paginator->paginatorWithParameters($queryBuilder, $request, 8, $routeName, 'categorySelected', $categorySelected);

//        list($producteurs, $pagerHtml) = $this->paginatorByCategory($queryBuilder, $request, 8, $routeName, 'categorySelected', $categorySelected);

        return $this->render('producteurs/index.html.twig', array(
                    'producteurs' => $producteurs,
                    'categories' => $categories,
                    'categorySelected' => $categorySelected,
                    'categorySelectedObject' => $categorySelectedObject,
                    'pagerHtml' => $pagerHtml,
        ));
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
        $repository = $this->getDoctrine()->getManager()
                ->getRepository('PanierfoyenBundle:Produits');

        $listProduits = $repository->getAllProduits($producteur);

        return $this->render('producteurs/show.produits.html.twig', array(
                    'producteur' => $producteur,
                    'produits' => $listProduits
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
