<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @param RecipeRepository $recipeRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(RecipeRepository $recipeRepository,PaginatorInterface $paginator,Request $request)
    {
//        $recipe=new Recipe();
//        $recipe->setName("Mlawi")
//            ->setPreparation("preparation de la Mlawi thon fromage 3dham ");
//        $em->persist($recipe);
//        $em->flush();

//        $recipe=$recipeRepository->find(1);
        $recipe=$paginator->paginate($recipeRepository->findAllRecentQuery(),
            $request->query->getInt('page',1),12);
//        $recipe=$recipeRepository->findAllRecent();
        dump($recipe);


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'recipes'=>$recipe
        ]);
    }

    //c'est une fonction de test

    /**
     * @Route("/increment", name="increment")
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    public function test(RecipeRepository $recipeRepository)
    {
        $recipe=$recipeRepository->findAllSpaghetti();
        dump($recipe);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'TEST',
            'recipes'=>$recipe
        ]);
    }

}
