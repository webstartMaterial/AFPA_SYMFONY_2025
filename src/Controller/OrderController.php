<?php

namespace App\Controller;

use App\Form\OrderFilterType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(OrderRepository $orderRepository, Request $request): Response
    {

        $orders = $orderRepository->findAll();

        // Créer le formulaire de filtre
        $form = $this->createForm(OrderFilterType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()) {

            

        }

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
            'form' => $form
        ]);
    }

    #[Route('/order/{id}', name: 'app_order_details', methods:'GET' )]
    public function getOrderDetails(int $id, OrderRepository $orderRepository): Response
    {

        $order = $orderRepository->find($id);

        return $this->render('order/show.html.twig', [
            'order' => $order,
            'date' => $order->getDate(),
            'total_amount' => $order->getAmount(),
            'order_details' => $order->getOrderDetails(),
            'user' => $order->getUser()
        ]);
    }
}
