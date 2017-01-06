<?php

namespace PanierfoyenBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoriesRepository extends EntityRepository {

    public function getAllWithProduits() {

        $entity = $this->_em
                ->getRepository('PanierfoyenBundle:Categories')
                ->createQueryBuilder('e')
                ->addSelect('r')
                ->join('e.produits', 'r')
                ->orderBy('e.libelle', 'ASC')
                ->getQuery();
//                ->getResult();
        return $entity;
    }
    
    public function getAllWithProduitsAndVariations() {

        $entity = $this->_em
                ->getRepository('PanierfoyenBundle:Categories')
                ->createQueryBuilder('e')
                ->addSelect('r')
                ->addSelect('s')
                ->join('e.produits', 'r')
                ->join('r.les_conditionnements', 's')
                ->orderBy('e.libelle', 'ASC')
                ->addOrderBy('r.libelle', 'ASC')
                ->getQuery();
//                ->getResult();
        return $entity;
    }

    public function getAllWithProducteurs() {
        $entity = $this->_em
                ->getRepository('PanierfoyenBundle:Categories')
                ->createQueryBuilder('e')
                ->addSelect('r')
                ->join('e.producteur', 'r')
                ->orderBy('e.libelle', 'ASC')
                ->getQuery();
//                ->getResult();
        return $entity;
    }

    public function getAllWithProducteursAndProduits() {
        $entity = $this->_em
                ->getRepository('PanierfoyenBundle:Categories')
                ->createQueryBuilder('e')
                ->addSelect('r')
                ->join('e.producteur', 'r')
                ->leftJoin('r.produits', 't')
                ->orderBy('e.libelle', 'ASC')
                ->getQuery();
//                ->getResult();
        return $entity;
    }
    
     public function getAllWithProducteursAndProduitsFiltered($categorieLibelle) {
        $entity = $this->_em
                ->getRepository('PanierfoyenBundle:Categories')
                ->createQueryBuilder('e')
                ->addSelect('r')
                ->join('e.producteur', 'r')
                ->leftJoin('r.produits', 't')
                ->andWhere('e.libelle = ?1')
                ->setParameter(1, $categorieLibelle)
                ->getQuery();
//                ->getResult();
        return $entity;
    }
    
    public function getAllOrderedByLibelle(){
        $entity = $this->_em
                ->getRepository('PanierfoyenBundle:Categories')
                ->createQueryBuilder('e')
                ->orderBy('e.libelle', 'ASC')
                ->getQuery();
//                ->getResult();
        return $entity;
    }

}
