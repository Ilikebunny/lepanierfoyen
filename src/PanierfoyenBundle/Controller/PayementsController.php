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

use PanierfoyenBundle\Entity\Payements;
use PanierfoyenBundle\Form\PayementsType;


/**
 * Payements controller.
 *
 * @Route("/payements")
 */
class PayementsController extends Controller
{
    /**
     * Lists all Payements entities.
     *
     * @Route("/", name="payements")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Payements')->createQueryBuilder('e');

        list($payements, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('payements/index.html.twig', array(
            'payements' => $payements,
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
            return $me->generateUrl('payements', array('page' => $page));
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
     * Displays a form to create a new Payements entity.
     *
     * @Route("/new", name="payements_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $payement = new Payements();
        $form   = $this->createForm('PanierfoyenBundle\Form\PayementsType', $payement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($payement);
            $em->flush();

            return $this->redirectToRoute('payements_show', array('id' => $payement->getId()));
        }
        return $this->render('payements/new.html.twig', array(
            'payement' => $payement,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a Payements entity.
     *
     * @Route("/{id}", name="payements_show")
     * @Method("GET")
     */
    public function showAction(Payements $payement)
    {
        $deleteForm = $this->createDeleteForm($payement);
        return $this->render('payements/show.html.twig', array(
            'payement' => $payement,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Payements entity.
     *
     * @Route("/{id}/edit", name="payements_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Payements $payement)
    {
        $deleteForm = $this->createDeleteForm($payement);
        $editForm = $this->createForm('PanierfoyenBundle\Form\PayementsType', $payement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($payement);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('payements_edit', array('id' => $payement->getId()));
        }
        return $this->render('payements/edit.html.twig', array(
            'payement' => $payement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Payements entity.
     *
     * @Route("/{id}", name="payements_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Payements $payement)
    {
    
        $form = $this->createDeleteForm($payement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($payement);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('payements');
    }
    
    /**
     * Creates a form to delete a Payements entity.
     *
     * @param Payements $payement The Payements entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Payements $payement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('payements_delete', array('id' => $payement->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Payements by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="payements_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id){

        $em = $this->getDoctrine()->getManager();
        $payement = $em->getRepository('PanierfoyenBundle:Payements')->find($id);
        
        if (!$payement) {
            throw $this->createNotFoundException('Unable to find Payements entity.');
        }
        
        try {
            $em->remove($payement);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('payements'));

    }
    
    
    
}
