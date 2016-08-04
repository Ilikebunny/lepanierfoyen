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

use PanierfoyenBundle\Entity\Disponibilites;
use PanierfoyenBundle\Form\DisponibilitesType;


/**
 * Disponibilites controller.
 *
 * @Route("/disponibilites")
 */
class DisponibilitesController extends Controller
{
    /**
     * Lists all Disponibilites entities.
     *
     * @Route("/", name="disponibilites")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Disponibilites')->createQueryBuilder('e');

        list($disponibilites, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('disponibilites/index.html.twig', array(
            'disponibilites' => $disponibilites,
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
            return $me->generateUrl('disponibilites', array('page' => $page));
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
     * Displays a form to create a new Disponibilites entity.
     *
     * @Route("/new", name="disponibilites_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $disponibilite = new Disponibilites();
        $form   = $this->createForm('PanierfoyenBundle\Form\DisponibilitesType', $disponibilite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($disponibilite);
            $em->flush();

            return $this->redirectToRoute('disponibilites_show', array('id' => $disponibilite->getId()));
        }
        return $this->render('disponibilites/new.html.twig', array(
            'disponibilite' => $disponibilite,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a Disponibilites entity.
     *
     * @Route("/{id}", name="disponibilites_show")
     * @Method("GET")
     */
    public function showAction(Disponibilites $disponibilite)
    {
        $deleteForm = $this->createDeleteForm($disponibilite);
        return $this->render('disponibilites/show.html.twig', array(
            'disponibilite' => $disponibilite,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Disponibilites entity.
     *
     * @Route("/{id}/edit", name="disponibilites_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Disponibilites $disponibilite)
    {
        $deleteForm = $this->createDeleteForm($disponibilite);
        $editForm = $this->createForm('PanierfoyenBundle\Form\DisponibilitesType', $disponibilite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($disponibilite);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('disponibilites_edit', array('id' => $disponibilite->getId()));
        }
        return $this->render('disponibilites/edit.html.twig', array(
            'disponibilite' => $disponibilite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Disponibilites entity.
     *
     * @Route("/{id}", name="disponibilites_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Disponibilites $disponibilite)
    {
    
        $form = $this->createDeleteForm($disponibilite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($disponibilite);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('disponibilites');
    }
    
    /**
     * Creates a form to delete a Disponibilites entity.
     *
     * @param Disponibilites $disponibilite The Disponibilites entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Disponibilites $disponibilite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('disponibilites_delete', array('id' => $disponibilite->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Disponibilites by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="disponibilites_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id){

        $em = $this->getDoctrine()->getManager();
        $disponibilite = $em->getRepository('PanierfoyenBundle:Disponibilites')->find($id);
        
        if (!$disponibilite) {
            throw $this->createNotFoundException('Unable to find Disponibilites entity.');
        }
        
        try {
            $em->remove($disponibilite);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('disponibilites'));

    }
    
    
    
}
