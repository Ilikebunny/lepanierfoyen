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
use PanierfoyenBundle\Entity\Producteurs;
use PanierfoyenBundle\Form\ProducteursType;

/**
 * AdminProducteurs controller.
 *
 * @Route("/admin/producteurs")
 */
class AdminProducteursController extends Controller {

    /**
     * Lists all Producteurs entities.
     *
     * @Route("/", name="admin_producteurs")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Producteurs')->createQueryBuilder('e');

        list($producteurs, $pagerHtml) = $this->paginator($queryBuilder, $request);

        return $this->render('producteurs/admin/index.html.twig', array(
                    'producteurs' => $producteurs,
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
            return $me->generateUrl('producteurs', array('page' => $page));
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
     * Displays a form to create a new Producteurs entity.
     *
     * @Route("/new", name="producteurs_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        $producteur = new Producteurs();
        $form = $this->createForm('PanierfoyenBundle\Form\ProducteursType', $producteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($producteur);
            $em->flush();

            return $this->redirectToRoute('producteurs_show', array('id' => $producteur->getId()));
        }
        return $this->render('producteurs/admin/new.html.twig', array(
                    'producteur' => $producteur,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Producteurs entity.
     *
     * @Route("/{id}", name="admin_producteurs_show")
     * @Method("GET")
     */
    public function showAction(Producteurs $producteur) {
        $deleteForm = $this->createDeleteForm($producteur);
        return $this->render('producteurs/show.html.twig', array(
                    'producteur' => $producteur,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Producteurs entity.
     *
     * @Route("/{id}/edit", name="producteurs_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Producteurs $producteur) {
        $deleteForm = $this->createDeleteForm($producteur);
        $editForm = $this->createForm('PanierfoyenBundle\Form\ProducteursType', $producteur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($producteur);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('producteurs_edit', array('id' => $producteur->getId()));
        }
        return $this->render('producteurs/admin/edit.html.twig', array(
                    'producteur' => $producteur,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Producteurs entity.
     *
     * @Route("/{id}", name="producteurs_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Producteurs $producteur) {

        $form = $this->createDeleteForm($producteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($producteur);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('producteurs');
    }

    /**
     * Creates a form to delete a Producteurs entity.
     *
     * @param Producteurs $producteur The Producteurs entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Producteurs $producteur) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('producteurs_delete', array('id' => $producteur->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Delete Producteurs by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="producteurs_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id) {

        $em = $this->getDoctrine()->getManager();
        $producteur = $em->getRepository('PanierfoyenBundle:Producteurs')->find($id);

        if (!$producteur) {
            throw $this->createNotFoundException('Unable to find Producteurs entity.');
        }

        try {
            $em->remove($producteur);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('producteurs'));
    }

}
