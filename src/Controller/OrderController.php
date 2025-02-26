<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderFilterType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(OrderRepository $orderRepository, Request $request): Response
    {


        // Créer le formulaire de filtre
        $form = $this->createForm(OrderFilterType::class);
        $form->handleRequest($request);

        $queryBuilder = $orderRepository->createQueryBuilder('o')
        ->leftJoin('o.user', 'u')
        ->addSelect('u');

        if($form->isSubmitted()) {

            // je récupère toutes les données du formulaire
            $data = $form->getData();

            if(!empty($data["orderNumber"])) {
                $queryBuilder->andWhere('o.id LIKE :orderNumber')
                ->setParameter('orderNumber', '%' . $data["orderNumber"] . '%');
            }
            
            if(!empty($data["status"])) {
                $queryBuilder->andWhere('o.status = :status')
                ->setParameter('status', $data["status"]);
            }

            if(!empty($data["user"])) {
                $queryBuilder->andWhere('u.email LIKE :userEmail OR u.id = :userId')
                ->setParameter('userEmail', '%' . $data["user"] . '%')
                ->setParameter('userId', (int) $data["user"]);
            }

            $orders = $queryBuilder->getQuery()->getResult();

        } else {
            $orders = $orderRepository->findAll();
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

    #[Route('/order/update-status/{id}', name: 'app_order_update_status', methods:'POST' )]
    public function updateStatus(Order $order, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        if(isset($data['status'])) {
            $order->setStatus($data['status']);
            $entityManager->persist($order);
            $entityManager->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false], 400);

    }
}
