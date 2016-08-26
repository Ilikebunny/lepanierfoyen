<?php

namespace PanierfoyenBundle\Controller\admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PanierfoyenBundle\Entity\ContentDynamic;
use PanierfoyenBundle\Form\ContentDynamicType;

/**
 * ContentDynamic controller.
 *
 * @Route("/admin/contentdynamic")
 */
class AdminContentDynamicController extends Controller {

    /**
     * Lists all ContentDynamic entities.
     *
     * @Route("/", name="admin_contentdynamic")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:ContentDynamic')->createQueryBuilder('e');

        //Pagination
        $paginator = $this->container->get('panierfoyen.paginator');
        list($contentDynamics, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 10, 'admin_contentdynamic');

        return $this->render('contentdynamic/admin/index.html.twig', array(
                    'contentDynamics' => $contentDynamics,
                    'pagerHtml' => $pagerHtml,
        ));
    }

    /**
     * Displays a form to create a new ContentDynamic entity.
     *
     * @Route("/new", name="contentdynamic_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        $contentDynamic = new ContentDynamic();
        $form = $this->createForm('PanierfoyenBundle\Form\ContentDynamicType', $contentDynamic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contentDynamic);
            $em->flush();

            return $this->redirectToRoute('admin_contentdynamic', array('id' => $contentDynamic->getId()));
        }
        return $this->render('contentdynamic/admin/new.html.twig', array(
                    'contentDynamic' => $contentDynamic,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ContentDynamic entity.
     *
     * @Route("/{id}", name="contentdynamic_show")
     * @Method("GET")
     */
    public function showAction(ContentDynamic $contentDynamic) {
        return $this->render('contentdynamic/show.html.twig', array(
                    'contentDynamic' => $contentDynamic,
        ));
    }
    
    /**
     * Finds and displays a ContentDynamic entity.
     *
     * @Route("/preview/{id}", name="contentdynamic_preview")
     * @Method("GET")
     */
    public function previewAction(ContentDynamic $contentDynamic) {
        return $this->render('contentdynamic/show.html.twig', array(
                    'contentDynamic' => $contentDynamic,
        ));
    }

    /**
     * Displays a form to edit an existing ContentDynamic entity.
     *
     * @Route("/{id}/edit", name="contentdynamic_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ContentDynamic $contentDynamic) {
        $deleteForm = $this->createDeleteForm($contentDynamic);
        $editForm = $this->createForm('PanierfoyenBundle\Form\ContentDynamicType', $contentDynamic);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contentDynamic);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('contentdynamic_edit', array('id' => $contentDynamic->getId()));
        }
        return $this->render('contentdynamic/admin/edit.html.twig', array(
                    'contentDynamic' => $contentDynamic,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ContentDynamic entity.
     *
     * @Route("/{id}", name="contentdynamic_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ContentDynamic $contentDynamic) {

        $form = $this->createDeleteForm($contentDynamic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contentDynamic);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_contentdynamic');
    }

    /**
     * Creates a form to delete a ContentDynamic entity.
     *
     * @param ContentDynamic $contentDynamic The ContentDynamic entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ContentDynamic $contentDynamic) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('contentdynamic_delete', array('id' => $contentDynamic->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Delete ContentDynamic by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="contentdynamic_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id) {

        $em = $this->getDoctrine()->getManager();
        $contentDynamic = $em->getRepository('PanierfoyenBundle:ContentDynamic')->find($id);

        if (!$contentDynamic) {
            throw $this->createNotFoundException('Unable to find ContentDynamic entity.');
        }

        try {
            $em->remove($contentDynamic);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('admin_contentdynamic'));
    }

}
