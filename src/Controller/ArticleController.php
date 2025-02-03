<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/article/{id}', name: 'show_article')]
    public function getArticleInfo(int $id, ArticleRepository $articleRepository): Response
    {
        // récupérer en bdd les informations liées à l'article avec l'id = {id}
        $article = $articleRepository->find($id);
        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }

    // REST
    // ARTICLE

    // /article (GET) => AFFICHE TOUS MES ARTICLES
    // /article (POST) => AJOUTE UN ARTICLE EN BDD
    // /article/1 (GET) => AFFICHE MOI L'ARTICLE QUI A L'ID 1
    // /article/1 (PUT) => MODIFIE MOI L'ARTICLE QUI A L'ID 1
    // /article/1 (DELETE) => SUPPRIME MOI L'ARTICLE QUI A L'ID 1


}


