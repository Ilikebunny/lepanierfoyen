<?php

namespace PanierfoyenBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProduitsRepository extends EntityRepository {

    public function getAllProduits($producteur) {

        $repository = $this->_em
                ->getRepository('PanierfoyenBundle:Produits');

//        $listProduits = $repository->findByProducteur($producteur->getId());
        $listProduits = $repository->findBy(array('producteur' => $producteur->getId()));
        return $listProduits;
    }

    public function getAllOrderedByCategory() {
        $entity = $this->_em
                ->getRepository('PanierfoyenBundle:Produits')
                ->createQueryBuilder('e')
                ->join('e.category', 'r')
                ->orderBy('r.libelle', 'ASC')
                ->getQuery();
//                ->getResult();
        return $entity;
    }

}
