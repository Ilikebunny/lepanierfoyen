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

use PanierfoyenBundle\Entity\Contrats;
use PanierfoyenBundle\Form\ContratsType;


/**
 * Contrats controller.
 *
 * @Route("/contrats")
 */
class ContratsController extends Controller
{
    /**
     * Lists all Contrats entities.
     *
     * @Route("/", name="contrats")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Contrats')->createQueryBuilder('e');

        list($contrats, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('contrats/index.html.twig', array(
            'contrats' => $contrats,
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
            return $me->generateUrl('contrats', array('page' => $page));
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
     * Displays a form to create a new Contrats entity.
     *
     * @Route("/new", name="contrats_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $contrat = new Contrats();
        $form   = $this->createForm('PanierfoyenBundle\Form\ContratsType', $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contrat);
            $em->flush();

            return $this->redirectToRoute('contrats_show', array('id' => $contrat->getId()));
        }
        return $this->render('contrats/new.html.twig', array(
            'contrat' => $contrat,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a Contrats entity.
     *
     * @Route("/{id}", name="contrats_show")
     * @Method("GET")
     */
    public function showAction(Contrats $contrat)
    {
        $deleteForm = $this->createDeleteForm($contrat);
        return $this->render('contrats/show.html.twig', array(
            'contrat' => $contrat,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Contrats entity.
     *
     * @Route("/{id}/edit", name="contrats_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Contrats $contrat)
    {
        $deleteForm = $this->createDeleteForm($contrat);
        $editForm = $this->createForm('PanierfoyenBundle\Form\ContratsType', $contrat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contrat);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('contrats_edit', array('id' => $contrat->getId()));
        }
        return $this->render('contrats/edit.html.twig', array(
            'contrat' => $contrat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Contrats entity.
     *
     * @Route("/{id}", name="contrats_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Contrats $contrat)
    {
    
        $form = $this->createDeleteForm($contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contrat);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('contrats');
    }
    
    /**
     * Creates a form to delete a Contrats entity.
     *
     * @param Contrats $contrat The Contrats entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contrats $contrat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contrats_delete', array('id' => $contrat->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Contrats by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="contrats_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id){

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('PanierfoyenBundle:Contrats')->find($id);
        
        if (!$contrat) {
            throw $this->createNotFoundException('Unable to find Contrats entity.');
        }
        
        try {
            $em->remove($contrat);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('contrats'));

    }
    
    
    
}
