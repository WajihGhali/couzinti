<?php

namespace App\Repository;

use App\Entity\Rank;
use App\Entity\RecipeSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Rank|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rank|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rank[]    findAll()
 * @method Rank[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RankRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rank::class);
    }



    /**
     * @param $value
     * @return int
     */

    public function calcul($value)
    {

        try {
            return $this->createQueryBuilder('p')
                ->andWhere('p.rankRecipe = :value')
                ->setParameter('value', $value)
                ->select('AVG(p.mark) as fortunesPrinted')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
            return 0;
        }

    }

//    /**
//     * @param $value
//     * @return int
//     */
//
//    public function countRank($value)
//    {
//
//        try {
//            return $this->createQueryBuilder('p')
//                ->andWhere('p.rankRecipe = :value')
//                ->setParameter('value', $value)
//                ->select('COUNT(p.mark) as fortunesPrinted')
//                ->getQuery()
//                ->getSingleScalarResult();
//        } catch (NoResultException $e) {
//        } catch (NonUniqueResultException $e) {
//            return 0;
//        }
//
//    }



    // /**
    //  * @return Rank[] Returns an array of Rank objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rank
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
