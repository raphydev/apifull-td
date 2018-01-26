<?php

namespace Labs\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PromotionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PromotionRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getListQB()
    {
        $qb = $this->createQueryBuilder('promo');
        return $qb;
    }

    /**
     * @param $product
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getListWithParamsQB($product)
    {
        $qb = $this->createQueryBuilder('promo');
        $qb->where($qb->expr()->eq('promo.product', ':product'));
        $qb->setParameter('product', $product);
        return $qb;
    }

    /**
     * @param $product
     * @param $promotion
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getPromoByProductId($product, $promotion){

        $qb = $this->createQueryBuilder('promo');
        $qb->where('promo.id = :promotion');
        $qb->andWhere('promo.product = :product');
        $qb->setParameter('promotion', $promotion);
        $qb->setParameter('product', $product);
        return $qb;
    }

    /**
     * @param $product
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllPromotionByProductId($product){

        $qb = $this->createQueryBuilder('promotion');
        $qb->Where('promotion.product = :product');
        $qb->setParameter('product', $product);
        return $qb;
    }
}
