<?php

namespace PanierfoyenBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProduitsRepository extends EntityRepository {

    public function getAllProduits($producteur) {

        $repository = $this->_em
                ->getRepository('PanierfoyenBundle:Produits');

        $listProduits = $repository->findByProducteur($producteur->getId());
        $listProduits = $repository->findBy(array('producteur' => $producteur->getId()));
        return $listProduits;

        $queryBuilder = $this->createQueryBuilder('p')
                ->leftJoin('p.producteur', 'r', 'WITH', 'p.producteur = ' . $producteur->getId())
        ;

        return $queryBuilder
                        ->getQuery()
                        ->getResult()

        ;
    }

}
