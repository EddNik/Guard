<?php

namespace AppBundle\Repository;

class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllArticle()
    {
        return $this->getEntityManager()
            ->getRepository('AppBundle:ArticleTest')
            ->createQueryBuilder('a');
    }
}