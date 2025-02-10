<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        return $this->render('cart/index.html.twig', [
            'cart_items' => $session->get('cart'),
            'total_amount' => 0
        ]);
    }

    #[Route('/cart/{idArticle}', name: 'app_add_cart', methods: ['POST'])]
    public function addArticleToCart(int $idArticle, Request $request, ArticleRepository $articleRepository): Response
    {

        // créer la session
        $session = $request->getSession();

        // si elle existe pas je la créé
        if (!$session->get('cart')) {
            $session->set('cart', [
                "id" => [],
                "title" => [],
                "description" => [],
                "stock" => [],
                "price" => [],
                "picture" => [],
                "quantity" => [],
            ]);
        }
        // je la récupère
        $cartSession = $session->get('cart');

        // je récupère les infos du produit en bdd que je souhaite ajouter à mon panier
        $product = $articleRepository->find($idArticle);

        // j'alimente ma sessions panier avec les infos du produit

        $cartSession["id"][] = $product->getId();
        $cartSession["title"][] = $product->getTitle();
        $cartSession["description"][] = $product->getDescription();
        $cartSession["stock"][] = $product->getStock();
        $cartSession["price"][] = $product->getPrice();
        $cartSession["picture"][] = $product->getPicture();
        $cartSession["quantity"][] = 1;

        // mettre à jour la session
        $session->set('cart', $cartSession);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/delete/{idArticle}', name: 'app_delete_cart')]
    public function deleteProductFromCart(int $idArticle, Request $request, ArticleRepository $articleRepository): Response
    {
        // récupérer la session
        $session = $request->getSession();
        $cartSession = $session->get('cart', []);

        // Vérifier si l'article existe dans le panier
        if (($key = array_search($idArticle, $cartSession['id'])) !== false) {
            // Supprimer toutes les informations liées à cet article
            unset($cartSession['id'][$key]);
            unset($cartSession['title'][$key]);
            unset($cartSession['description'][$key]);
            unset($cartSession['stock'][$key]);
            unset($cartSession['price'][$key]);
            unset($cartSession['picture'][$key]);
            unset($cartSession['quantity'][$key]);

            // Réindexer les tableaux pour éviter les trous dans les clés
            $cartSession['id'] = array_values($cartSession['id']);
            $cartSession['title'] = array_values($cartSession['title']);
            $cartSession['description'] = array_values($cartSession['description']);
            $cartSession['stock'] = array_values($cartSession['stock']);
            $cartSession['price'] = array_values($cartSession['price']);
            $cartSession['picture'] = array_values($cartSession['picture']);
            $cartSession['quantity'] = array_values($cartSession['quantity']);
        }

        // Mettre à jour la session avec le nouveau panier
        $session->set('cart', $cartSession);

        // Rediriger vers la page du panier
        return $this->redirectToRoute('app_cart');
    }
}
