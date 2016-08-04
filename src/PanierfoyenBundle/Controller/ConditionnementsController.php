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

use PanierfoyenBundle\Entity\Conditionnements;
use PanierfoyenBundle\Form\ConditionnementsType;


/**
 * Conditionnements controller.
 *
 * @Route("/conditionnements")
 */
class ConditionnementsController extends Controller
{
    /**
     * Lists all Conditionnements entities.
     *
     * @Route("/", name="conditionnements")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Conditionnements')->createQueryBuilder('e');

        list($conditionnements, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('conditionnements/index.html.twig', array(
            'conditionnements' => $conditionnements,
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
            return $me->generateUrl('conditionnements', array('page' => $page));
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
     * Displays a form to create a new Conditionnements entity.
     *
     * @Route("/new", name="conditionnements_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $conditionnement = new Conditionnements();
        $form   = $this->createForm('PanierfoyenBundle\Form\ConditionnementsType', $conditionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($conditionnement);
            $em->flush();

            return $this->redirectToRoute('conditionnements_show', array('id' => $conditionnement->getId()));
        }
        return $this->render('conditionnements/new.html.twig', array(
            'conditionnement' => $conditionnement,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a Conditionnements entity.
     *
     * @Route("/{id}", name="conditionnements_show")
     * @Method("GET")
     */
    public function showAction(Conditionnements $conditionnement)
    {
        $deleteForm = $this->createDeleteForm($conditionnement);
        return $this->render('conditionnements/show.html.twig', array(
            'conditionnement' => $conditionnement,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Conditionnements entity.
     *
     * @Route("/{id}/edit", name="conditionnements_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Conditionnements $conditionnement)
    {
        $deleteForm = $this->createDeleteForm($conditionnement);
        $editForm = $this->createForm('PanierfoyenBundle\Form\ConditionnementsType', $conditionnement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($conditionnement);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('conditionnements_edit', array('id' => $conditionnement->getId()));
        }
        return $this->render('conditionnements/edit.html.twig', array(
            'conditionnement' => $conditionnement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Conditionnements entity.
     *
     * @Route("/{id}", name="conditionnements_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Conditionnements $conditionnement)
    {
    
        $form = $this->createDeleteForm($conditionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($conditionnement);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('conditionnements');
    }
    
    /**
     * Creates a form to delete a Conditionnements entity.
     *
     * @param Conditionnements $conditionnement The Conditionnements entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Conditionnements $conditionnement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('conditionnements_delete', array('id' => $conditionnement->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Conditionnements by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="conditionnements_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id){

        $em = $this->getDoctrine()->getManager();
        $conditionnement = $em->getRepository('PanierfoyenBundle:Conditionnements')->find($id);
        
        if (!$conditionnement) {
            throw $this->createNotFoundException('Unable to find Conditionnements entity.');
        }
        
        try {
            $em->remove($conditionnement);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('conditionnements'));

    }
    
    
    
}
