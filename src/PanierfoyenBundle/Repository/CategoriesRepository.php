<?php

namespace PanierfoyenBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoriesRepository extends EntityRepository {

    public function getAllWithProduits() {

        $entity = $this->_em
                ->getRepository('PanierfoyenBundle:Categories')
                ->createQueryBuilder('e')
                ->join('e.produits', 'r')
                ->orderBy('e.libelle', 'ASC')
                ->getQuery();
//                ->getResult();
        return $entity;
    }

}
