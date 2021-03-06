<?php

namespace Labs\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{

    public function getListQB()
    {
        $qb = $this->createQueryBuilder('c');
        return $qb;
    }

    /**
     * @param $department
     * @param $category
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCategoryByDepartmentId($department, $category)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where('c.id = :category');
        $qb->andWhere('c.department = :department');
        $qb->setParameter('category', $category);
        $qb->setParameter('department', $department);
        return $qb;
    }

}
