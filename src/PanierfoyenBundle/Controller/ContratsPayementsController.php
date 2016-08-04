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

use PanierfoyenBundle\Entity\ContratsPayements;
use PanierfoyenBundle\Form\ContratsPayementsType;


/**
 * ContratsPayements controller.
 *
 * @Route("/contratspayements")
 */
class ContratsPayementsController extends Controller
{
    /**
     * Lists all ContratsPayements entities.
     *
     * @Route("/", name="contratspayements")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:ContratsPayements')->createQueryBuilder('e');

        list($contratsPayements, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('contratspayements/index.html.twig', array(
            'contratsPayements' => $contratsPayements,
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
            return $me->generateUrl('contratspayements', array('page' => $page));
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
     * Displays a form to create a new ContratsPayements entity.
     *
     * @Route("/new", name="contratspayements_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $contratsPayement = new ContratsPayements();
        $form   = $this->createForm('PanierfoyenBundle\Form\ContratsPayementsType', $contratsPayement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contratsPayement);
            $em->flush();

            return $this->redirectToRoute('contratspayements_show', array('id' => $contratsPayement->getId()));
        }
        return $this->render('contratspayements/new.html.twig', array(
            'contratsPayement' => $contratsPayement,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a ContratsPayements entity.
     *
     * @Route("/{id}", name="contratspayements_show")
     * @Method("GET")
     */
    public function showAction(ContratsPayements $contratsPayement)
    {
        $deleteForm = $this->createDeleteForm($contratsPayement);
        return $this->render('contratspayements/show.html.twig', array(
            'contratsPayement' => $contratsPayement,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing ContratsPayements entity.
     *
     * @Route("/{id}/edit", name="contratspayements_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ContratsPayements $contratsPayement)
    {
        $deleteForm = $this->createDeleteForm($contratsPayement);
        $editForm = $this->createForm('PanierfoyenBundle\Form\ContratsPayementsType', $contratsPayement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contratsPayement);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('contratspayements_edit', array('id' => $contratsPayement->getId()));
        }
        return $this->render('contratspayements/edit.html.twig', array(
            'contratsPayement' => $contratsPayement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a ContratsPayements entity.
     *
     * @Route("/{id}", name="contratspayements_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ContratsPayements $contratsPayement)
    {
    
        $form = $this->createDeleteForm($contratsPayement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contratsPayement);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('contratspayements');
    }
    
    /**
     * Creates a form to delete a ContratsPayements entity.
     *
     * @param ContratsPayements $contratsPayement The ContratsPayements entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ContratsPayements $contratsPayement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contratspayements_delete', array('id' => $contratsPayement->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete ContratsPayements by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="contratspayements_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id){

        $em = $this->getDoctrine()->getManager();
        $contratsPayement = $em->getRepository('PanierfoyenBundle:ContratsPayements')->find($id);
        
        if (!$contratsPayement) {
            throw $this->createNotFoundException('Unable to find ContratsPayements entity.');
        }
        
        try {
            $em->remove($contratsPayement);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('contratspayements'));

    }
    
    
    
}
