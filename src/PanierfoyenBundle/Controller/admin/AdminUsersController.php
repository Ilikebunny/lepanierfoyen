<?php

namespace PanierfoyenBundle\Controller\admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;
use PanierfoyenBundle\Entity\Users;
use PanierfoyenBundle\Form\UsersType;
use PanierfoyenBundle\Form\UsersRightsType;

/**
 * Users controller.
 *
 * @Route("/admin/users")
 */
class AdminUsersController extends Controller {

    /**
     * Lists all Users entities.
     *
     * @Route("/", name="admin_users")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Users')->createQueryBuilder('e');

        list($users, $pagerHtml) = $this->paginator($queryBuilder, $request);

        return $this->render('users/index.html.twig', array(
                    'users' => $users,
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
            return $me->generateUrl('users', array('page' => $page));
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
     * Displays a form to manage users rights.
     *
     * @Route("/droits/{id}", name="admin_users_rights")
     * @Method({"GET", "POST"})
     */
    public function manageRightsAction(Request $request, Users $user) {

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
     * Displays a form to create a new Users entity.
     *
     * @Route("/new", name="users_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        $user = new Users();
        $form = $this->createForm('PanierfoyenBundle\Form\UsersType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('users_show', array('id' => $user->getId()));
        }
        return $this->render('users/new.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Users entity.
     *
     * @Route("/{id}", name="users_show")
     * @Method("GET")
     */
    public function showAction(Users $user) {
        $deleteForm = $this->createDeleteForm($user);
        return $this->render('users/show.html.twig', array(
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
        return $this->render('users/edit.html.twig', array(
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

        return $this->redirectToRoute('users');
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

        return $this->redirect($this->generateUrl('users'));
    }

}
