<?php

namespace Ecommerce\EcommerceBundle\Repository;

/**
 * ProduitsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitsRepository extends \Doctrine\ORM\EntityRepository
{
    public function  byCategorie($categorie)
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.categorie = :categorie')
            ->orderBy('u.id')
            ->setParameter('categorie', $categorie)
            ->getQuery()
            ->getResult();
    }

    public function recherche($chaine)
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.nom LIKE :categorie')
            ->andWhere('u.disponible = 1')
            ->orderBy('u.id')
            ->setParameter('categorie', '%'.$chaine.'%')
            ->getQuery()
            ->getResult();
    }

    public function findArray($array)
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.id IN (:array)')
            ->setParameter('array', $array)
            ->getQuery()
            ->getResult();
    }
}
