<?php

namespace PanierfoyenBundle\Controller\admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PanierfoyenBundle\Entity\Users;
use PanierfoyenBundle\Form\UsersType;
use PanierfoyenBundle\Form\UsersRightsType;

/**
 * Users controller.
 *
 * @Route("/admin/users")
 */
class AdminUsersController extends Controller {

    private function initBreadcrumbs() {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->prependRouteItem("Accueil", "_welcome");
        $breadcrumbs->addRouteItem("Administration", "admin_dashboard");
        $breadcrumbs->addRouteItem("Utilisateurs", "admin_users");
        return $breadcrumbs;
    }

    /**
     * Lists all Users entities.
     *
     * @Route("/", name="admin_users")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $breadcrumbs = $this->initBreadcrumbs();
        
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Users')->createQueryBuilder('e');

        //Pagination
        $paginator = $this->container->get('panierfoyen.paginator');
        list($entities, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 10, 'admin_users');

        return $this->render('users/admin/index.html.twig', array(
                    'users' => $entities,
                    'pagerHtml' => $pagerHtml,
        ));
    }

    /**
     * Displays a form to manage users rights.
     *
     * @Route("/droits/{id}", name="admin_users_rights")
     * @Method({"GET", "POST"})
     */
    public function manageRightsAction(Request $request, Users $user) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem("Gestion des droits");
        
        $form = $this->createForm('PanierfoyenBundle\Form\UsersRightsType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_users', array('id' => $user->getId()));
        }
        return $this->render('users/admin/rights.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Users entity.
     *
     * @Route("/{id}", name="users_preview")
     * @Method("GET")
     */
    public function showAction(Users $user) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem("Prévisualisation");
        
        $deleteForm = $this->createDeleteForm($user);
        return $this->render('users/admin/show.html.twig', array(
                    'user' => $user,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Users entity.
     *
     * @Route("/{id}/edit", name="users_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Users $user) {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('PanierfoyenBundle\Form\UsersType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('users_edit', array('id' => $user->getId()));
        }
        return $this->render('users/admin/edit.html.twig', array(
                    'user' => $user,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Users entity.
     *
     * @Route("/{id}", name="users_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Users $user) {

        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_users');
    }

    /**
     * Creates a form to delete a Users entity.
     *
     * @param Users $user The Users entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Users $user) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('users_delete', array('id' => $user->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Delete Users by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="users_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id) {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('PanierfoyenBundle:Users')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find Users entity.');
        }

        try {
            $em->remove($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_users');
    }

}
