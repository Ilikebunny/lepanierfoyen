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
use PanierfoyenBundle\Entity\Articles;
use PanierfoyenBundle\Form\ArticlesType;

/**
 * Articles controller.
 *
 * @Route("/articles")
 */
class ArticlesController extends Controller {

    /**
     * Lists all Articles entities.
     *
     * @Route("/", name="articles")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository('PanierfoyenBundle:Tags')->findAll();

        $queryBuilder = $em->getRepository('PanierfoyenBundle:Articles')
                ->createQueryBuilder('e')
                ->where('e.published = 1')
                ->orderBy('e.publicationDate', 'DESC')
        ;

        list($articles, $pagerHtml) = $this->paginator($queryBuilder, $request);

        return $this->render('articles/index.html.twig', array(
                    'articles' => $articles,
                    'pagerHtml' => $pagerHtml,
                    'tags' => $tags,
        ));
    }

    public function recentArticlesAction($max = 3) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:Articles')
                ->createQueryBuilder('e')
                ->where('e.published = 1')
                ->orderBy('e.publicationDate', 'DESC')
                ->setMaxResults($max)
        ;
        $articles = $queryBuilder->getQuery()->getResult();

        return $this->render(
                        'articles/recent_list.html.twig', array('recent_articles' => $articles)
        );
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
            return $me->generateUrl('articles', array('page' => $page));
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
     * Lists all Articles entities filtered by tag
     *
     * @Route("/tag/{tagLibelle}", name="articlesByTag")
     * @Method("GET")
     */
    public function indexTagAction(Request $request, $tagLibelle) {
        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository('PanierfoyenBundle:Tags')->findAll();
        $tagSelected = $em->getRepository('PanierfoyenBundle:Tags')->findOneByTitle($tagLibelle);

        $queryBuilder = $em->getRepository('PanierfoyenBundle:Articles')
                ->createQueryBuilder('e')
                ->where('e.published = 1')
                ->orderBy('e.publicationDate', 'DESC')
        ;

        list($articles, $pagerHtml) = $this->paginator($queryBuilder, $request);

        return $this->render('articles/index.html.twig', array(
                    'articles' => $articles,
                    'pagerHtml' => $pagerHtml,
                    'tagSelectedLibelle' => $tagLibelle,
                    'tags' => $tags,
                    'tagSelected' => $tagSelected,
        ));
    }

    /**
     * Finds and displays a Articles entity.
     *
     * @Route("/{slug}", name="articles_show", requirements={"slug": "[^/]++"})
     * @Method("GET")
     */
    public function showAction(Articles $article) {
        return $this->render('articles/show.html.twig', array(
                    'article' => $article,
        ));
    }

}
