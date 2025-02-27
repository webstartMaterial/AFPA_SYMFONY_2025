<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Form\UserProfileType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class ProfileController extends AbstractController
{

    //// PROTÉGER ET SÉCURISER UN ROUTE
    /// security.yaml
    /// annotation
    /// directement dans le controlleur


    #[Route('/profile', name: 'app_profile')]
    // #[IsGranted('ROLE_USER')] // Seuls les utilisateurs connectés peuvent accéder
    public function index( OrderRepository $orderRepository, 
        EntityManagerInterface $entityManager, 
        Request $request, 
        UserPasswordHasherInterface $passwordHasher,
        Security $security,
        SluggerInterface $slugger): Response
    {

        // Security $security
        // if (!$security->getUser()) {
        //     return new RedirectResponse('/login'); // Redirige vers la page de connexion
        // }

        $orders = $orderRepository->findBy(["user" => $this->getUser()->getId()]);

        $user = $security->getUser();


        ///// INFO DU PROFIL
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du mot de passe s'il est renseigné

            $photoProfile = $form->get('picture')->getData(); // récupère le fichier

            if($photoProfile) {

                $originalFilename = pathinfo($photoProfile->getClientOriginalName(), PATHINFO_FILENAME);
                // Récupère le nom original du fichier sans son extension.
                
                $safeFilename = $slugger->slug($originalFilename);
                // Transforme le nom original en une version sécurisée (supprime les caractères spéciaux, espaces, etc.).
                
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoProfile->guessExtension();
                // Génère un nom de fichier unique en ajoutant un identifiant unique (`uniqid()`) et en conservant l'extension d'origine.

                try {
                    $photoProfile->move(
                        $this->getParameter('profile_pictures_directory'),
                        $newFilename
                    );
                    $user->setPicture($newFilename); // Mise à jour du champ `picture` dans l'entité

                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload de l\'image.');
                }

            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès !');

            return $this->redirectToRoute('app_profile');
        }

        /// MODIFICATION DU MOT DE PASSE

        $formPassword = $this->createForm(ChangePasswordType::class, $user);
        $formPassword->handleRequest($request);

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            // Gestion du mot de passe s'il est renseigné

            $oldPassword = $formPassword->get('oldPassword')->getData();
            $newPassword = $formPassword->get('newPassword')->getData();

            // vérifier que l'ancien mot de passe est le bon
            if(!$passwordHasher->isPasswordValid($user, $oldPassword)) {
                $this->addFlash('danger', 'Ancien mot de passe incorrect !');
            } else {

                // hasher le nouveau mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe correctement modifié');
                return $this->redirectToRoute('app_profile');
            }

        }

        return $this->render('profile/index.html.twig', [
            'orders' => $orders,
            'userProfileForm' => $form->createView(),
            'formPassword' => $formPassword->createView(),

        ]);
    }

       


}
