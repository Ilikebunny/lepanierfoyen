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
use PanierfoyenBundle\Entity\Tags;
use PanierfoyenBundle\Form\TagsType;

/**
 * Tags controller.
 *
 * @Route("/tags")
 */
class TagsController extends Controller {

    /**
     * Lists all Tags entities.
     *
     * @Route("/", name="tags_list")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Tags')->createQueryBuilder('e');

        list($tags, $pagerHtml) = $this->paginator($queryBuilder, $request);

        return $this->render('tags/index.html.twig', array(
                    'tags' => $tags,
                    'pagerHtml' => $pagerHtml,
        ));
    }

    public function getAllTagsForNavAction() {
        $em = $this->getDoctrine()->getManager();
        $tagsForNav = $em->getRepository('PanierfoyenBundle:Tags')->findBy([], ['title' => 'ASC']);
        return $this->render(
                        'nav_tags.html.twig', array('tagsForNav' => $tagsForNav)
        );
    }

}
