<?php

namespace PanierfoyenBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProducteursRepository extends EntityRepository {

    public function getAllProduits($producteur) {

//        $repository = $this->_em
//                ->getRepository('PanierfoyenBundle:Produits');
//
//        $listProduits = $repository->findByProducteur($producteur->getId());
//        $listProduits = $repository->findBy(array('producteur' => $producteur->getId()));
//        return $listProduits;

//        $queryBuilder = $this->_em->createQueryBuilder()
//                ->select(array('p1', 'p2'))
//                ->from('Produits', 'p1')
//                ->leftJoin('p1.producteur', 'p2', 'WITH', 'p1.producteur_id = ' . $producteur->getId())
//
//        ;
//
//        return $queryBuilder
//                        ->getQuery()
//                        ->getResult()
//
//        ;
    }
    
    public function getAllOrderedByCategoryAndName(){
        $entity = $this->_em
                ->getRepository('PanierfoyenBundle:Producteurs')
                ->createQueryBuilder('e')
                ->addSelect('r')
                ->join('e.category', 'r')
                ->orderBy('r.libelle', 'ASC')
                ->orderBy('e.nom', 'ASC')
                ->getQuery();
//                ->getResult();
        return $entity;
    }

}
