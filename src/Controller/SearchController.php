<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
