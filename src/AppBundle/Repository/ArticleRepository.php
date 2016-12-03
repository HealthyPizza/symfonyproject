<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function getFiveLast($page=1,$size=3)
    {
        
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM AppBundle:Article a ORDER BY a.date DESC'
            )
            ->setFirstResult(($page-1)*$size)
            ->setMaxResults($size)
            ->getResult();
        return $result;
    }
}