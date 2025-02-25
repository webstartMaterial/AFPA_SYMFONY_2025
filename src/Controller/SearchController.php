<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {

        $search = $request->query->get('search');

        $pagination = $paginator->paginate(
            $articleRepository->findArticlesBySearch($search), /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            5 /* limit per page */
        );

        return $this->render('search/index.html.twig', [
            // 'articles' => $articles,
            'articles' => $pagination,
            'search' => $search
        ]);
    }

    #[Route('/search/suggestions', name: 'app_search_suggestions', methods:['GET'])]
    public function getSuggestions(Request $request, ArticleRepository $articleRepository): JsonResponse
    {

        $search = $request->query->get('search');

        if(!$search) {
            return $this->json([], 200); // réponse positive du serveur mais je renvois ici un tableau vide
        }

        $articles = $articleRepository->findArticlesBySearch($search);

        $suggestions = [];
        foreach ($articles as $article) {
            
            $suggestions[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle()
            ];
            
        }

        return $this->json($suggestions);

    }

}
