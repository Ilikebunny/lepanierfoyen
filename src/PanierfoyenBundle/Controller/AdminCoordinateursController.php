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
use PanierfoyenBundle\Entity\Coordinateurs;
use PanierfoyenBundle\Form\CoordinateursType;

/**
 * Coordinateurs controller.
 *
 * @Route("/admin/coordinateurs")
 */
class AdminCoordinateursController extends Controller {

    /**
     * Lists all Coordinateurs entities.
     *
     * @Route("/", name="admin_coordinateurs")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Coordinateurs')->createQueryBuilder('e');

        list($coordinateurs, $pagerHtml) = $this->paginator($queryBuilder, $request);

        return $this->render('coordinateurs/admin/index.html.twig', array(
                    'coordinateurs' => $coordinateurs,
                    'pagerHtml' => $pagerHtml,
        ));
    }

    /**
     * Get results from paginator and get paginator view.
     *
     */
    protected function paginator($queryBuilder, $request) {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $request->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me) {
            return $me->generateUrl('coordinateurs', array('page' => $page));
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
     * Displays a form to create a new Coordinateurs entity.
     *
     * @Route("/new", name="coordinateurs_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        $coordinateur = new Coordinateurs();
        $form = $this->createForm('PanierfoyenBundle\Form\CoordinateursType', $coordinateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($coordinateur);
            $em->flush();

            return $this->redirectToRoute('coordinateurs_show', array('id' => $coordinateur->getId()));
        }
        return $this->render('coordinateurs/admin/new.html.twig', array(
                    'coordinateur' => $coordinateur,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Coordinateurs entity.
     *
     * @Route("/{id}/edit", name="coordinateurs_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Coordinateurs $coordinateur) {
        $deleteForm = $this->createDeleteForm($coordinateur);
        $editForm = $this->createForm('PanierfoyenBundle\Form\CoordinateursType', $coordinateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($coordinateur);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('coordinateurs_edit', array('id' => $coordinateur->getId()));
        }
        return $this->render('coordinateurs/admin/edit.html.twig', array(
                    'coordinateur' => $coordinateur,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Coordinateurs entity.
     *
     * @Route("/{id}", name="coordinateurs_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Coordinateurs $coordinateur) {

        $form = $this->createDeleteForm($coordinateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($coordinateur);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('coordinateurs');
    }

    /**
     * Creates a form to delete a Coordinateurs entity.
     *
     * @param Coordinateurs $coordinateur The Coordinateurs entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Coordinateurs $coordinateur) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('coordinateurs_delete', array('id' => $coordinateur->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Delete Coordinateurs by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="coordinateurs_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id) {

        $em = $this->getDoctrine()->getManager();
        $coordinateur = $em->getRepository('PanierfoyenBundle:Coordinateurs')->find($id);

        if (!$coordinateur) {
            throw $this->createNotFoundException('Unable to find Coordinateurs entity.');
        }

        try {
            $em->remove($coordinateur);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('coordinateurs'));
    }

}
