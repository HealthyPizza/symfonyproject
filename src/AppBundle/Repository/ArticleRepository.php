<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    private $hasNext=true;
    private $pageCount=-1; //dummy value
    
    
    public function updatePageCount($size=5){
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT count(a.id) FROM AppBundle:Article a'
            )
            ->getSingleScalarResult();
        $this->pageCount=(int) ($result/$size);
        return $this->pageCount;
        
    }
    
    public function getPage($page=1,$size=5)
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
    
    public function getPageCount($size=5){
        if($this->pageCount==-1)
            $this->updatePageCount($size);
        return $this->pageCount;
    }
}