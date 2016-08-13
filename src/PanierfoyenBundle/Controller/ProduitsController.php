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

use PanierfoyenBundle\Entity\Produits;

/**
 * Produits controller.
 *
 * @Route("/produits")
 */
class ProduitsController extends Controller
{
    /**
     * Lists all Produits entities.
     *
     * @Route("/", name="produits")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Produits')->createQueryBuilder('e');

        list($produits, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('produits/index.html.twig', array(
            'produits' => $produits,
            'pagerHtml' => $pagerHtml,

        ));
    }


    /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder, $request)
    {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $request->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me)
        {
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
     * Displays a form to create a new Produits entity.
     *
     * @Route("/new", name="produits_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $produit = new Produits();
        $form   = $this->createForm('PanierfoyenBundle\Form\ProduitsType', $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produits_show', array('id' => $produit->getId()));
        }
        return $this->render('produits/new.html.twig', array(
            'produit' => $produit,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a Produits entity.
     *
     * @Route("/{id}", name="produits_show")
     * @Method("GET")
     */
    public function showAction(Produits $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        return $this->render('produits/show.html.twig', array(
            'produit' => $produit,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Produits entity.
     *
     * @Route("/{id}/edit", name="produits_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Produits $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        $editForm = $this->createForm('PanierfoyenBundle\Form\ProduitsType', $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('produits_edit', array('id' => $produit->getId()));
        }
        return $this->render('produits/edit.html.twig', array(
            'produit' => $produit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Produits entity.
     *
     * @Route("/{id}", name="produits_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Produits $produit)
    {
    
        $form = $this->createDeleteForm($produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('produits');
    }
    
    /**
     * Creates a form to delete a Produits entity.
     *
     * @param Produits $produit The Produits entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produits $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('produits_delete', array('id' => $produit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Produits by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="produits_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id){

        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('PanierfoyenBundle:Produits')->find($id);
        
        if (!$produit) {
            throw $this->createNotFoundException('Unable to find Produits entity.');
        }
        
        try {
            $em->remove($produit);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('produits'));

    }
    
    
    
}
