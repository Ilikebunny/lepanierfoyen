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

}
