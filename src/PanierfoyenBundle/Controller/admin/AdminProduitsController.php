<?php

namespace PanierfoyenBundle\Controller\admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PanierfoyenBundle\Entity\Produits;
use PanierfoyenBundle\Entity\Conditionnements;
use PanierfoyenBundle\Form\ConditionnementsType;

/**
 * Produits controller.
 *
 * @Route("/admin/produits")
 */
class AdminProduitsController extends Controller {

    private function initBreadcrumbs() {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->prependRouteItem("Accueil", "_welcome");
        $breadcrumbs->addRouteItem("Administration", "admin_dashboard");
        $breadcrumbs->addRouteItem("Produits", "admin_produits");
        return $breadcrumbs;
    }

    /**
     * Lists all Produits entities.
     *
     * @Route("/", name="admin_produits")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $breadcrumbs = $this->initBreadcrumbs();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Produits')->createQueryBuilder('e');

        $paginator = $this->container->get('panierfoyen.paginator');
        list($produits, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 12, 'admin_produits');

        return $this->render('produits/admin/index.html.twig', array(
                    'produits' => $produits,
                    'pagerHtml' => $pagerHtml,
        ));
    }

    /**
     * Displays a form to create a new Produits entity.
     *
     * @Route("/new", name="produits_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem("Ajouter");

        $produit = new Produits();
        $form = $this->createForm('PanierfoyenBundle\Form\ProduitsType', $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produits_prewiev', array('id' => $produit->getId()));
        }
        return $this->render('produits/admin/new.html.twig', array(
                    'produit' => $produit,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Produits entity.
     *
     * @Route("/preview/{id}", name="produits_prewiev")
     * @Method("GET")
     */
    public function showAction(Produits $produit) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem("Prévisualiser");

        $deleteForm = $this->createDeleteForm($produit);
        return $this->render('produits/admin/show.html.twig', array(
                    'produit' => $produit,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Produits entity.
     *
     * @Route("/{id}/edit", name="produits_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Produits $produit) {
        $deleteForm = $this->createDeleteForm($produit);
        $editForm = $this->createForm('PanierfoyenBundle\Form\ProduitsType', $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('produits_edit', array('id' => $produit->getId()));
        }
        return $this->render('produits/admin/edit.html.twig', array(
                    'produit' => $produit,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Produits entity.
     *
     * @Route("/conditionnements/edit/{id}", name="admin_produits_conditionnements")
     * @Method({"GET", "POST"})
     */
    public function editConditionnementsAction(Request $request, Produits $produit) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem("Gérer les conditionnements");

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Conditionnements')->createQueryBuilder('e');

        $conditionnements = $produit->getLes_conditionnements();
//        list($conditionnements, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $conditionnement = new Conditionnements();
        $conditionnement->setProduit($produit);

        $form = $this->createForm('PanierfoyenBundle\Form\ConditionnementsType', $conditionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($conditionnement);
            $em->flush();

            return $this->redirectToRoute('admin_produits_conditionnements', array(
//                        'conditionnements' => $conditionnements,
//                        'produit' => $produit,
//                        'form' => $form->createView(),
                        'id' => $produit->getId(),
            ));
        }

        return $this->render('produits/admin/conditionnements.html.twig', array(
                    'conditionnements' => $conditionnements,
                    'produit' => $produit,
                    'form' => $form->createView(),
                    'temp' => $conditionnement,
        ));
    }

    /**
     * Deletes a Produits entity.
     *
     * @Route("/{id}", name="produits_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Produits $produit) {

        $form = $this->createDeleteForm($produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_produits');
    }

    /**
     * Creates a form to delete a Produits entity.
     *
     * @param Produits $produit The Produits entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produits $produit) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('produits_delete', array('id' => $produit->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Delete Produits by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="produits_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id) {

        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('PanierfoyenBundle:Produits')->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Unable to find Produits entity.');
        }

        try {
            $em->remove($produit);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('produits'));
    }

}
