<?php

namespace App\Repository;

use App\Entity\IngredientSearch;
use App\Entity\Recipe;
use App\Entity\RecipeSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function findAllRecipe(RecipeSearch $recipeSearch)
    {
        return $this->createQueryBuilder('r')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllRecipeVisible():QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ;
    }





//    public function findOneByIdAndAllItsProducts(IngredientSearch $ingredientSearch)
//    {
//        return $this->createQueryBuilder('re')
//            ->addselect('re,in')
//            ->join('App\Entity\Ingredient', 'in', 'WITH', 'in.recipe = re.id')
//            ->andwhere('re.nameIng = :ingredient')
//            ->setParameter('ingredient', $ingredientSearch)
//            ->getQuery()
//            ->getResult();
//    }


    public function findJoinedToIngredient($ingredientSearch)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT p, c FROM App\Entity\Recipe p
            JOIN p.ingredient c
            WHERE c.nameIng = :ingredient'
            )->setParameter('ingredient', $ingredientSearch);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
        }
    }



//function de test des query

    /**
     * @return Recipe[] Returns an array of Recipe objects
     */
    public function findAllRecent()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $recipeName
     * @return Recipe[] Returns an array of Recipe objects
     */
    public function findAllSpaghetti($recipeName)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name = :val')
            ->setParameter('val', $recipeName)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }




    // /**
    //  * @return Recipe[] Returns an array of Recipe objects
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
    public function findOneBySomeField($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Query
     */
    public function findAllRecentQuery():Query
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ;
    }


}
