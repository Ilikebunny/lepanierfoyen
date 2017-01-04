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

    public function getAllWithProducteursAndProduits($offset, $limit) {
        $entity = $this->_em
                ->getRepository('PanierfoyenBundle:Categories')
                ->createQueryBuilder('e')
                ->addSelect('r')
                ->join('e.producteur', 'r')
                ->leftJoin('r.produits', 't')
                ->orderBy('e.libelle', 'ASC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
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
