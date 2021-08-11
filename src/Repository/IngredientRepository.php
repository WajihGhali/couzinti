<?php

namespace App\Repository;

use App\Entity\Ingredient;
use App\Entity\IngredientSearch;
use App\Entity\RecipeSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }




    public function findAllIngredientVisible():QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ;
    }


    /**
     * @param IngredientSearch $ingredientSearch
     * @return Ingredient[] Returns an array of Recipe objects
     */

    public function FindAllVisibleIngredientQuery(IngredientSearch $ingredientSearch)
    {
        $qb= $this->findAllIngredientVisible();

        if($ingredientSearch->getIngredientSearch())
        {
            $query=$qb->andWhere('i.nameIng = :ingredient');
            $query->setParameter('ingredient',$ingredientSearch->getIngredientSearch());
        }


        $query=$qb->getQuery();
        return $query->execute();
    }

    // /**
    //  * @return Ingredient[] Returns an array of Ingredient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ingredient
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
