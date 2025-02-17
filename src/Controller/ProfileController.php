<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{

    //// PROTÉGER ET SÉCURISER UN ROUTE
    /// security.yaml
    /// annotation
    /// directement dans le controlleur


    #[Route('/profile', name: 'app_profile')]
    // #[IsGranted('ROLE_USER')] // Seuls les utilisateurs connectés peuvent accéder
    public function index( OrderRepository $orderRepository): Response
    {

        // Security $security
        // if (!$security->getUser()) {
        //     return new RedirectResponse('/login'); // Redirige vers la page de connexion
        // }

        $orders = $orderRepository->findBy(["user" => $this->getUser()->getId()]);
    
        return $this->render('profile/index.html.twig', [
            'orders' => $orders,
        ]);
    }
}
