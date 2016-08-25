<?php

namespace PanierfoyenBundle\Controller\admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PanierfoyenBundle\Entity\Categories;
use PanierfoyenBundle\Form\CategoriesType;

/**
 * Categories controller.
 *
 * @Route("/admin/categories")
 */
class AdminCategoriesController extends Controller {

    /**
     * Lists all Categories entities.
     *
     * @Route("/", name="admin_categories")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Categories')->createQueryBuilder('e');

        //Pagination
        $paginator = $this->container->get('panierfoyen.paginator');
        list($entities, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 10, 'admin_categories');

        return $this->render('categories/admin/index.html.twig', array(
                    'categories' => $entities,
                    'pagerHtml' => $pagerHtml,
        ));
    }

    /**
     * Displays a form to create a new Categories entity.
     *
     * @Route("/new", name="categories_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        $category = new Categories();
        $form = $this->createForm('PanierfoyenBundle\Form\CategoriesType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_categories');
        }
        return $this->render('categories/admin/new.html.twig', array(
                    'category' => $category,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Categories entity.
     *
     * @Route("/{id}/edit", name="categories_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Categories $category) {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('PanierfoyenBundle\Form\CategoriesType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('categories_edit', array('id' => $category->getId()));
        }
        return $this->render('categories/admin/edit.html.twig', array(
                    'category' => $category,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Categories entity.
     *
     * @Route("/{id}", name="categories_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Categories $category) {

        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_categories');
    }

    /**
     * Creates a form to delete a Categories entity.
     *
     * @param Categories $category The Categories entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categories $category) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('categories_delete', array('id' => $category->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Delete Categories by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="categories_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id) {

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('PanierfoyenBundle:Categories')->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find Categories entity.');
        }

        try {
            $em->remove($category);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_categories');
    }

}
