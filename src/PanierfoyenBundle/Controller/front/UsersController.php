<?php

namespace PanierfoyenBundle\Controller\front;

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

/**
 * Users controller.
 *
 * @Route("/monProfil")
 */
class UsersController extends Controller {

    /**
     * Finds and displays a Users entity.
     *
     * @Route("/edit", name="myprofil_edit")
     * @Method({"GET", "POST"})
     */
    public function editProfilAction(Request $request) {
        
        $user = $this->getUser();
        $editForm = $this->createForm('PanierfoyenBundle\Form\UsersType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('myprofil_edit');
        }
        return $this->render('users/profil/edit.html.twig', array(
                    'user' => $user,
                    'edit_form' => $editForm->createView(),
        ));
        
    }

    /**
     * Finds and displays a Users entity.
     *
     * @Route("/{id}", name="users_show")
     * @Method("GET")
     */
    public function showAction(Users $user) {
        return $this->render('users/show.html.twig', array(
                    'user' => $user,
        ));
    }

}
