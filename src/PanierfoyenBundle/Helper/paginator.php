<?php

namespace PanierfoyenBundle\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

class paginator {

    private $container;
    private $router;

    public function __construct(ContainerInterface $container, Router $router) {
        $this->container = $container;
        $this->router = $router;
    }

    /**
     * Get results from paginator and get paginator view.
     *
     */
    public function paginatorSimple($queryBuilder, $request, $maxPerPage, $routeName) {
        // Paginator
        $pagerfanta = $this->manageEntities($queryBuilder, $request, $maxPerPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this->router;
        $routeGenerator = function($page) use ($me, $routeName) {
            return $me->generate($routeName, array(
                        'page' => $page
            ));
        };

        // Paginator - view
        $pagerHtml = $this->generatePager($pagerfanta, $routeGenerator);

        return array($entities, $pagerHtml);
    }

    public function paginatorWithParameters($queryBuilder, $request, $maxPerPage, $routeName, $routeParamName, $routeParamValue) {
        // Paginator
        $pagerfanta = $this->manageEntities($queryBuilder, $request, $maxPerPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this->router;
        $routeGenerator = function($page) use ($me, $routeName, $routeParamName, $routeParamValue) {
            return $me->generate($routeName, array(
                        'page' => $page,
                        $routeParamName => $routeParamValue,
            ));
        };

        // Paginator - view
        $pagerHtml = $this->generatePager($pagerfanta, $routeGenerator);

        return array($entities, $pagerHtml);
    }

    private function manageEntities($queryBuilder, $request, $maxPerPage) {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($maxPerPage);
        $currentPage = $request->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        return $pagerfanta;
    }

    private function generatePager($pagerfanta, $routeGenerator) {
        $view = new TwitterBootstrap3View();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => 'previous',
            'next_message' => 'next',
        ));
        return $pagerHtml;
    }

}
