<?php

namespace Labs\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MediaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MediaRepository extends EntityRepository
{
    public function getListQB()
    {
        $qb = $this->createQueryBuilder('m');
        return $qb;
    }

    public function getMediaByProduct($product, $media)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where('m.id = :media');
        $qb->andWhere('m.product = :product');
        $qb->setParameter('product', $product);
        $qb->setParameter('media', $media);
        return $qb;
    }
}
