<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserRegisterEvent;
use App\Form\UserType;
use App\Security\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="user_register")
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @param EventDispatcherInterface $eventDispatcher
     * @param TokenGenerator $tokenGenerator
     * @return Response
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder,
                             Request $request,
                             EventDispatcherInterface $eventDispatcher,
                             TokenGenerator $tokenGenerator)
    {
        $user = new User();
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $password=$passwordEncoder->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($password);
            $user->setConfirmationToken($tokenGenerator->getRandomSecureToken(30));
            $user->setRoles(['ROLE_USER']);
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $userRegisterEvent = new UserRegisterEvent($user);
            $eventDispatcher->dispatch($userRegisterEvent,UserRegisterEvent::NAME);

            return $this->redirectToRoute('home');
        }
        return $this->render('register/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
