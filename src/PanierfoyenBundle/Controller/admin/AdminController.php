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
use PanierfoyenBundle\Entity\Categories;
use PanierfoyenBundle\Form\CategoriesType;

/**
 * Admin controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller {

    /**
     * Lists all Categories entities.
     *
     * @Route("/", name="admin_dashboard")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        return $this->render('admin_dashboard.html.twig');
    }
}
