<?php

namespace PanierfoyenBundle\Controller\front;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PanierfoyenBundle\Entity\ContentDynamic;
use PanierfoyenBundle\Form\ContentDynamicType;

/**
 * ContentDynamic controller.
 *
 * @Route("/contentdynamic")
 */
class ContentDynamicController extends Controller {

    /**
     * Lists all ContentDynamic entities.
     *
     * @Route("/", name="contentdynamic")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:ContentDynamic')->createQueryBuilder('e');

        //Pagination
        $paginator = $this->container->get('panierfoyen.paginator');
        list($contentDynamics, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 10, 'admin_contentdynamic');

        return $this->render('contentdynamic/admin/index.html.twig', array(
                    'contentDynamics' => $contentDynamics,
                    'pagerHtml' => $pagerHtml,
        ));
    }

    /**
     * Finds and displays a ContentDynamic entity.
     *
     * @Route("/{id}", name="contentdynamic_show")
     * @Method("GET")
     */
    public function showAction(ContentDynamic $contentDynamic) {
        return $this->render('contentdynamic/show.html.twig', array(
                    'contentDynamic' => $contentDynamic,
        ));
    }

}
