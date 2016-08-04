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

use PanierfoyenBundle\Entity\Distributions;
use PanierfoyenBundle\Form\DistributionsType;


/**
 * Distributions controller.
 *
 * @Route("/distributions")
 */
class DistributionsController extends Controller
{
    /**
     * Lists all Distributions entities.
     *
     * @Route("/", name="distributions")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Distributions')->createQueryBuilder('e');

        list($distributions, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('distributions/index.html.twig', array(
            'distributions' => $distributions,
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
            return $me->generateUrl('distributions', array('page' => $page));
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
     * Displays a form to create a new Distributions entity.
     *
     * @Route("/new", name="distributions_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $distribution = new Distributions();
        $form   = $this->createForm('PanierfoyenBundle\Form\DistributionsType', $distribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($distribution);
            $em->flush();

            return $this->redirectToRoute('distributions_show', array('id' => $distribution->getId()));
        }
        return $this->render('distributions/new.html.twig', array(
            'distribution' => $distribution,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a Distributions entity.
     *
     * @Route("/{id}", name="distributions_show")
     * @Method("GET")
     */
    public function showAction(Distributions $distribution)
    {
        $deleteForm = $this->createDeleteForm($distribution);
        return $this->render('distributions/show.html.twig', array(
            'distribution' => $distribution,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Distributions entity.
     *
     * @Route("/{id}/edit", name="distributions_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Distributions $distribution)
    {
        $deleteForm = $this->createDeleteForm($distribution);
        $editForm = $this->createForm('PanierfoyenBundle\Form\DistributionsType', $distribution);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($distribution);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('distributions_edit', array('id' => $distribution->getId()));
        }
        return $this->render('distributions/edit.html.twig', array(
            'distribution' => $distribution,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Distributions entity.
     *
     * @Route("/{id}", name="distributions_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Distributions $distribution)
    {
    
        $form = $this->createDeleteForm($distribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($distribution);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('distributions');
    }
    
    /**
     * Creates a form to delete a Distributions entity.
     *
     * @param Distributions $distribution The Distributions entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Distributions $distribution)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('distributions_delete', array('id' => $distribution->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Distributions by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="distributions_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id){

        $em = $this->getDoctrine()->getManager();
        $distribution = $em->getRepository('PanierfoyenBundle:Distributions')->find($id);
        
        if (!$distribution) {
            throw $this->createNotFoundException('Unable to find Distributions entity.');
        }
        
        try {
            $em->remove($distribution);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('distributions'));

    }
    
    
    
}
