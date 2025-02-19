<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {

        $search = $request->query->get('search');
        $articles = $articleRepository->findArticlesBySearch($search);

        return $this->render('search/index.html.twig', [
            'articles' => $articles,
            'search' => $search
        ]);
    }
}
