<?php

namespace PanierfoyenBundle\Controller\admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PanierfoyenBundle\Entity\Articles;
use PanierfoyenBundle\Form\ArticlesType;

/**
 * Articles controller.
 *
 * @Route("/admin/articles")
 */
class AdminArticlesController extends Controller {

    private function initBreadcrumbs() {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->prependRouteItem("Accueil", "_welcome");
        $breadcrumbs->addRouteItem("Administration", "admin_dashboard");
        $breadcrumbs->addRouteItem("Articles", "admin_articles");
        return $breadcrumbs;
    }

    /**
     * Lists all Articles entities.
     *
     * @Route("/", name="admin_articles")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $breadcrumbs = $this->initBreadcrumbs();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Articles')->createQueryBuilder('e')
                ->orderBy('e.created', 'DESC');

        //Pagination
        $paginator = $this->container->get('panierfoyen.paginator');
        list($articles, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 10, 'admin_articles');

        return $this->render('articles/admin/index.html.twig', array(
                    'articles' => $articles,
                    'pagerHtml' => $pagerHtml,
        ));
    }

    /**
     * Displays a form to create a new Articles entity.
     *
     * @Route("/new", name="articles_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem("Ajouter");

        $article = new Articles();
        $form = $this->createForm('PanierfoyenBundle\Form\ArticlesType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_articles', array('id' => $article->getId()));
        }
        return $this->render('articles/admin/new.html.twig', array(
                    'article' => $article,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Articles entity.
     *
     * @Route("/{id}/edit", name="articles_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Articles $article) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem($article->getTitre() . " - Modifier");

        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('PanierfoyenBundle\Form\ArticlesType', $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('articles_edit', array('id' => $article->getId()));
        }
        return $this->render('articles/admin/edit.html.twig', array(
                    'article' => $article,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Articles entity.
     *
     * @Route("/{id}", name="articles_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Articles $article) {

        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_articles');
    }

    /**
     * Creates a form to delete a Articles entity.
     *
     * @param Articles $article The Articles entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Articles $article) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('articles_delete', array('id' => $article->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Delete Articles by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="articles_by_id_delete")
     * @Method("GET")
     */
    public function deleteById($id) {

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('PanierfoyenBundle:Articles')->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Unable to find Articles entity.');
        }

        try {
            $em->remove($article);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('admin_articles'));
    }

    /**
     * Delete Articles by id
     *
     * @param mixed $id The entity id
     * @Route("/publish/{id}", name="articles_by_id_publish")
     * @Method("GET")
     */
    public function publishById($id) {

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('PanierfoyenBundle:Articles')->findOneById($id);

        if (!$article) {
            throw $this->createNotFoundException('Unable to find Articles entity.');
        }

        try {
            $article->setPublished(TRUE);
            $em->persist($article);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('admin_articles'));
    }

    /**
     * Finds and displays a Articles entity.
     *
     * @Route("/preview/{slug}", name="articles_preview", requirements={"slug": "[^/]++"})
     * @Method("GET")
     */
    public function showAction(Articles $article) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem($article->getTitre() . " - Prévisualiser");

        return $this->render('articles/show.html.twig', array(
                    'article' => $article,
        ));
    }

}
