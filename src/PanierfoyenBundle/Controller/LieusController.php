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

use PanierfoyenBundle\Entity\Lieus;
use PanierfoyenBundle\Form\LieusType;


/**
 * Lieus controller.
 *
 * @Route("/admin/lieus")
 */
class LieusController extends Controller
{
    /**
     * Lists all Lieus entities.
     *
     * @Route("/", name="lieus")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Lieus')->createQueryBuilder('e');

        list($lieuses, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('lieus/index.html.twig', array(
            'lieuses' => $lieuses,
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
            return $me->generateUrl('lieus', array('page' => $page));
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
     * Displays a form to create a new Lieus entity.
     *
     * @Route("/new", name="lieus_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $lieus = new Lieus();
        $form   = $this->createForm('PanierfoyenBundle\Form\LieusType', $lieus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lieus);
            $em->flush();

            return $this->redirectToRoute('lieus_show', array('id' => $lieus->getId()));
        }
        return $this->render('lieus/new.html.twig', array(
            'lieus' => $lieus,
            'form'   => $form->createView(),
        ));
    }
    
    

    
    /**
     * Finds and displays a Lieus entity.
     *
     * @Route("/{id}", name="lieus_show")
     * @Method("GET")
     */
    public function showAction(Lieus $lieus)
    {
        $deleteForm = $this->createDeleteForm($lieus);
        return $this->render('lieus/show.html.twig', array(
            'lieus' => $lieus,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Lieus entity.
     *
     * @Route("/{id}/edit", name="lieus_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Lieus $lieus)
    {
        $deleteForm = $this->createDeleteForm($lieus);
        $editForm = $this->createForm('PanierfoyenBundle\Form\LieusType', $lieus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lieus);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('lieus_edit', array('id' => $lieus->getId()));
        }
        return $this->render('lieus/edit.html.twig', array(
            'lieus' => $lieus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Lieus entity.
     *
     * @Route("/{id}", name="lieus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Lieus $lieus)
    {
    
        $form = $this->createDeleteForm($lieus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lieus);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        
        return $this->redirectToRoute('lieus');
    }
    
    /**
     * Creates a form to delete a Lieus entity.
     *
     * @param Lieus $lieus The Lieus entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Lieus $lieus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lieus_delete', array('id' => $lieus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Lieus by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="lieus_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id){

        $em = $this->getDoctrine()->getManager();
        $lieus = $em->getRepository('PanierfoyenBundle:Lieus')->find($id);
        
        if (!$lieus) {
            throw $this->createNotFoundException('Unable to find Lieus entity.');
        }
        
        try {
            $em->remove($lieus);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('lieus'));

    }
    
    
    
}
