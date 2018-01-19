<?php

namespace Labs\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * PriceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class PriceRepository extends EntityRepository
{

    public function getListQB()
    {
        $qb = $this->createQueryBuilder('price');
        return $qb;
    }

    /**
     * @param $product
     * @param $price
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getPriceByproductId($product, $price)
    {
        $qb = $this->createQueryBuilder('price');
        $qb->where('price.id = :price');
        $qb->andWhere('price.product = :product');
        $qb->setParameter('product', $product);
        $qb->setParameter('price', $price);
        return $qb;
    }
}
