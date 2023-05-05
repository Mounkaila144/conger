<?php

namespace App\Controller;

use App\Entity\Agen;
use App\Entity\User;
use App\Form\RegistrationAgenFormType;
use App\Form\RegistrationFormType;
use App\Security\SecurityAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, SecurityAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_ADMIN']);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
 #[Route('/register/agen', name: 'app_register_agen')]
    public function registeragen(Request $request,AuthorizationCheckerInterface $authChecker, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, SecurityAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Agen();
        $form = $this->createForm(RegistrationAgenFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_AGEN']);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            if ($authChecker->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_agen_index'); // redirect the user to the homepage if they're already logged in
            }
        }

        return $this->render('registration/registerAgen.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}
