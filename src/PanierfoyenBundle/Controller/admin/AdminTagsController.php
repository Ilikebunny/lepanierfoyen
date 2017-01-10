<?php

namespace PanierfoyenBundle\Controller\admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PanierfoyenBundle\Entity\Tags;
use PanierfoyenBundle\Form\TagsType;

/**
 * Tags controller.
 *
 * @Route("/admin/tags")
 */
class AdminTagsController extends Controller {

    private function initBreadcrumbs() {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->prependRouteItem("Accueil", "_welcome");
        $breadcrumbs->addRouteItem("Administration", "admin_dashboard");
        $breadcrumbs->addRouteItem("Tags", "tags");
        return $breadcrumbs;
    }

    /**
     * Lists all Tags entities.
     *
     * @Route("/", name="tags")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $breadcrumbs = $this->initBreadcrumbs();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Tags')->createQueryBuilder('e');

        //Pagination
        $paginator = $this->container->get('panierfoyen.paginator');
        list($entities, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 10, 'tags');

        return $this->render('tags/index.html.twig', array(
                    'tags' => $entities,
                    'pagerHtml' => $pagerHtml,
        ));
    }

    /**
     * Displays a form to create a new Tags entity.
     *
     * @Route("/new", name="tags_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem("Ajouter");

        $tag = new Tags();
        $form = $this->createForm('PanierfoyenBundle\Form\TagsType', $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('tags');
        }
        return $this->render('tags/new.html.twig', array(
                    'tag' => $tag,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Tags entity.
     *
     * @Route("/{id}", name="tags_show")
     * @Method("GET")
     */
    public function showAction(Tags $tag) {
        $deleteForm = $this->createDeleteForm($tag);
        return $this->render('tags/show.html.twig', array(
                    'tag' => $tag,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Tags entity.
     *
     * @Route("/{id}/edit", name="tags_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tags $tag) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem("Modifier");

        $deleteForm = $this->createDeleteForm($tag);
        $editForm = $this->createForm('PanierfoyenBundle\Form\TagsType', $tag);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('tags_edit', array('id' => $tag->getId()));
        }
        return $this->render('tags/edit.html.twig', array(
                    'tag' => $tag,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Tags entity.
     *
     * @Route("/{id}", name="tags_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Tags $tag) {

        $form = $this->createDeleteForm($tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tag);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('tags');
    }

    /**
     * Creates a form to delete a Tags entity.
     *
     * @param Tags $tag The Tags entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tags $tag) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('tags_delete', array('id' => $tag->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Delete Tags by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="tags_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id) {

        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('PanierfoyenBundle:Tags')->find($id);

        if (!$tag) {
            throw $this->createNotFoundException('Unable to find Tags entity.');
        }

        try {
            $em->remove($tag);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('tags');
    }

}
