<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Ingredient;
use App\Entity\IngredientSearch;
use App\Entity\Rank;
use App\Entity\Recipe;
use App\Entity\RecipeSearch;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\IngredientSearchType;
use App\Form\RankType;
use App\Form\RecipeSearchType;
use App\Form\RecipeType;
use App\Repository\IngredientRepository;
use App\Repository\RankRepository;
use App\Repository\RecipeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/**
 * @Route("/recipe")
 */
class RecipeController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * @var RecipeRepository
     */
    private $repository;

    public function __construct(RecipeRepository $repository)
    {
        $this->repository = $repository;
//        $this->em = $em;

    }

    /**
     * @Route("/", name="recipe_index", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $ingredientSearch=new IngredientSearch();
        $form=$this->createForm(IngredientSearchType::class,$ingredientSearch);
        $form->handleRequest($request);
        $rank=new Rank();
        $recipeSearch=new RecipeSearch();
        $form_1=$this->createForm(RecipeSearchType::class,$recipeSearch);
        $form_1->handleRequest($request);
//        $em = $this->getDoctrine()->getManager();
//        $this->em->flush();
        dump($ingredientSearch);
        $ingredientSearch=$ingredientSearch->getIngredientSearch();

        dump($recipeSearch);
        $recipeSearch=$recipeSearch->getRecipeName();

        if ($recipeSearch!=null){
//            $recipes=$this->repository->findAllSpaghetti($recipeSearch);
            //or we can use thi tips
            $recipes=$this->repository->findBy(['name'=>$recipeSearch]);

        }

        elseif ($ingredientSearch!=null){
            $recipes=$this->repository->findJoinedToIngredient($ingredientSearch);
            dump($recipes);
        }
        else{
            $recipes=$this->repository->findAllRecent();
            dump($recipes);
        }

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
            'form'=>$form->createView(),
            'searchByUser'=> 0,
            'rank'=>$rank,
            'form_1'=>$form_1->createView()
        ]);
    }

    /**
     * @Route("/new", name="recipe_new", methods={"GET","POST"})
     * @param Request $request
     * @Security("is_granted('ROLE_USER')")
     * @return Response
     */
    public function new(Request $request): Response
    {
        $user=$this->getUser();
        $recipe = new Recipe();
        $recipe->setUser($user);

        $ingredient=new Ingredient();
        $ingredient->setNameIng('banana');
        $ingredient->setRecipe($recipe);
        $recipe->addIngredient($ingredient);
        dump($recipe);

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipe);
            $this->addFlash('notice','Recipe Created');
            $entityManager->flush();
            return $this->redirectToRoute('recipe_index');

        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_show", methods={"GET","POST"})
     * @param Recipe $recipe
     * @param Request $request
     * @param RankRepository $rankRepository
     * @return Response
     */
    public function show(Recipe $recipe,Request $request,RankRepository $rankRepository): Response
    {

        $rank=new Rank();
        $form = $this->createForm(RankType::class, $rank);
        $form->handleRequest($request);
        $rank->setRankRecipe($recipe);
        $rank->setRankUser($this->getUser());
        $ingredient=$recipe->getIngredient();

        $resultat=$rankRepository->calcul($rank->getRankRecipe());
//        $resultat2=$rankRepository->countRank($rank->getRankRecipe());
//        $average=$resultat/$resultat2;

        dump($resultat);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rank);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_index');
        }

        //comment control
        $comment= new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comment->setCommentRecipe($recipe);
        $comment->setCommentUser($this->getUser());
        if ($formComment->isSubmitted() && $formComment->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_index');
        }
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
            'rank'=>$rank,
            'rankRepository'=>$resultat,
            'ingredients'=>$ingredient,
            'comment'=>$comment,
            'formComment'=>$formComment->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="recipe_edit", methods={"GET","POST"})
     * @Security("is_granted('edit', recipe)", message="Access denied")
     * @param Request $request
     * @param Recipe $recipe
     * @return Response
     */
    public function edit(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice','Recipe Edited');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_delete", methods={"DELETE"})
     * @param Request $request
     * @param Recipe $recipe
     * @Security("is_granted('delete', recipe)", message="Access denied")
     * @return Response
     */
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recipe);
            $this->addFlash('notice','Recipe Deleted');
            $entityManager->flush();
        }

        return $this->redirectToRoute('recipe_index');
    }


    /**
     * @Route("/user/{username}",name="recipe_post_user")
     * @param User $userWithRecipe
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    public function userPosts(User $userWithRecipe,RecipeRepository $recipeRepository)
    {

        $html=$this->renderView('recipe/index.html.twig',[
            'recipes'=>$recipeRepository->findBy(
                ['user'=>$userWithRecipe]
            ),
            'user'=> $userWithRecipe,
            'searchByUser'=> 1

//            'posts'=>$userWithPosts->getPosts()
//        we can't use this and sort for exple with time
        ]);

        return new Response($html);
    }

}
