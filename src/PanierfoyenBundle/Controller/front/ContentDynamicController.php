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

    private function initBreadcrumbs() {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->prependRouteItem("Accueil", "_welcome");
        return $breadcrumbs;
    }
    /**
     * Lists all ContentDynamic entities.
     *
     * @Route("/", name="contentdynamic")
     * @Method("GET")
     */
//    public function indexAction(Request $request) {
//        $em = $this->getDoctrine()->getManager();
//        $queryBuilder = $em->getRepository('PanierfoyenBundle:ContentDynamic')->createQueryBuilder('e');
//
//        //Pagination
//        $paginator = $this->container->get('panierfoyen.paginator');
//        list($contentDynamics, $pagerHtml) = $paginator->paginatorSimple($queryBuilder, $request, 10, 'admin_contentdynamic');
//
//        return $this->render('contentdynamic/admin/index.html.twig', array(
//                    'contentDynamics' => $contentDynamics,
//                    'pagerHtml' => $pagerHtml,
//        ));
//    }

    /**
     * Finds and displays a Articles entity.
     *
     * @Route("/{slug}", name="contentdynamic_show", requirements={"slug": "[^/]++"})
     * @Method("GET")
     */
    public function showAction(ContentDynamic $contentDynamic) {
        $breadcrumbs = $this->initBreadcrumbs();
        $breadcrumbs->addItem($contentDynamic->getTitre());
        return $this->render('contentdynamic/show.html.twig', array(
                  'contentDynamic' => $contentDynamic,
        ));
    }

    public function generateNavAction() {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PanierfoyenBundle:ContentDynamic')
                ->createQueryBuilder('e')
//                ->where('e.published = 1')
                ->orderBy('e.order', 'ASC')
        ;
        $entities = $queryBuilder->getQuery()->getResult();

        return $this->render(
                        'contentdynamic/navbar_dynamicContent.html.twig', array('dynamicContents' => $entities)
        );
    }

}
