<?php

namespace PanierfoyenBundle\Controller\admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PanierfoyenBundle\Entity\Producteurs;
use PanierfoyenBundle\Form\ProducteursType;
use PanierfoyenBundle\Entity\Gallery;

/**
 * AdminProducteurs controller.
 *
 * @Route("/admin/producteurs")
 */
class AdminProducteursController extends Controller {

    private function initBreadcrumbs() {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->prependRouteItem("Accueil", "_welcome");
        $breadcrumbs->addRouteItem("Administration", "admin_dashboard");
        $breadcrumbs->addRouteItem("Contenu dynamique", "admin_contentdynamic");
        return $breadcrumbs;
    }
    
    /**
     * Lists all Producteurs entities.
     *
     * @Route("/", name="admin_producteurs")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $breadcrumbs = $this->initBreadcrumbs();
        
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Producteurs')->createQueryBuilder('e');
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Producteurs')->getAllOrderedByName();

        //Pagination
        $paginator = $this->container->get('panierfoyen.paginator');
        list($entities, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 10, 'admin_producteurs');

        return $this->render('producteurs/admin/index.html.twig', array(
                    'producteurs' => $entities,
                    'pagerHtml' => $pagerHtml,
        ));
    }

    /**
     * Displays a form to create a new Producteurs entity.
     *
     * @Route("/new", name="producteurs_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem("Ajouter");
        
        $producteur = new Producteurs();
        $form = $this->createForm('PanierfoyenBundle\Form\ProducteursType', $producteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($producteur);
            $em->flush();

            return $this->redirectToRoute('admin_producteurs', array('id' => $producteur->getId()));
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
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem("Prévisualiser");
        
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

        return $this->redirectToRoute('/admin/producteurs');
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

        return $this->redirectToRoute('admin_producteurs');
    }

    /**
     * Displays a form to edit an existing Produits entity.
     *
     * @Route("/gallery/edit/{id}", name="admin_producteur_gallery")
     * @Method({"GET", "POST"})
     */
    public function editGalleryAction(Request $request, Producteurs $producteur) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addRouteItem("Gérer gallerie", "admin_producteur_gallery");
        
        $em = $this->getDoctrine()->getManager();

        $gallery = $producteur->getMyGallery();

        $gallery_item = new Gallery();
        $gallery_item->setProducteur($producteur);

        $form = $this->createForm('PanierfoyenBundle\Form\GalleryType', $gallery_item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($gallery_item);
            $em->flush();

            return $this->redirectToRoute('admin_producteur_gallery', array(
//                        'conditionnements' => $conditionnements,
//                        'produit' => $produit,
//                        'form' => $form->createView(),
                        'id' => $producteur->getId(),
            ));
        }

        return $this->render('producteurs/admin/gallery.html.twig', array(
                    'gallery' => $gallery,
                    'producteur' => $producteur,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Delete Gallery by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/gallery/{id}", name="gallery_by_id_delete")
     * @Method("GET")
     */
    public function deleteGalleryById($id) {

        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository('PanierfoyenBundle:Gallery')->find($id);
        $producteur = $gallery->getProducteur();

        if (!$gallery) {
            throw $this->createNotFoundException('Unable to find Producteurs entity.');
        }

        try {
            $em->remove($gallery);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_producteur_gallery', array(
                    'id' => $producteur->getId(),
        ));
    }

}
