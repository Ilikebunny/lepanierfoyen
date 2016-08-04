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

use PanierfoyenBundle\Entity\Frequences;
use PanierfoyenBundle\Form\FrequencesType;


/**
 * Frequences controller.
 *
 * @Route("/frequences")
 */
class FrequencesController extends Controller
{
    /**
     * Lists all Frequences entities.
     *
     * @Route("/", name="frequences")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Frequences')->createQueryBuilder('e');

        list($frequences, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('frequences/index.html.twig', array(
            'frequences' => $frequences,
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
            return $me->generateUrl('frequences', array('page' => $page));
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
     * Displays a form to create a new Frequences entity.
     *
     * @Route("/new", name="frequences_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $frequence = new Frequences();
        $form   = $this->createForm('PanierfoyenBundle\Form\FrequencesType', $frequence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($frequence);
            $em->flush();

            return $this->redirectToRoute('frequences_show', array('id' => $frequence->getId()));
        }
        return $this->render('frequences/new.html.twig', array(
            'frequence' => $frequence,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a Frequences entity.
     *
     * @Route("/{id}", name="frequences_show")
     * @Method("GET")
     */
    public function showAction(Frequences $frequence)
    {
        $deleteForm = $this->createDeleteForm($frequence);
        return $this->render('frequences/show.html.twig', array(
            'frequence' => $frequence,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Frequences entity.
     *
     * @Route("/{id}/edit", name="frequences_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Frequences $frequence)
    {
        $deleteForm = $this->createDeleteForm($frequence);
        $editForm = $this->createForm('PanierfoyenBundle\Form\FrequencesType', $frequence);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($frequence);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('frequences_edit', array('id' => $frequence->getId()));
        }
        return $this->render('frequences/edit.html.twig', array(
            'frequence' => $frequence,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Frequences entity.
     *
     * @Route("/{id}", name="frequences_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Frequences $frequence)
    {
    
        $form = $this->createDeleteForm($frequence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($frequence);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('frequences');
    }
    
    /**
     * Creates a form to delete a Frequences entity.
     *
     * @param Frequences $frequence The Frequences entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Frequences $frequence)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('frequences_delete', array('id' => $frequence->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Frequences by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="frequences_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id){

        $em = $this->getDoctrine()->getManager();
        $frequence = $em->getRepository('PanierfoyenBundle:Frequences')->find($id);
        
        if (!$frequence) {
            throw $this->createNotFoundException('Unable to find Frequences entity.');
        }
        
        try {
            $em->remove($frequence);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('frequences'));

    }
    
    
    
}
