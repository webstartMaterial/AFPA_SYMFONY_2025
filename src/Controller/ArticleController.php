<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {

        // ArticleRepository $articleRepositor => injecter le repo de Article
        // et j'ai appelé une méthode prédéfinie de la couche ServiceEntityRepository qui me permet de récupérer
        // tous les articles en BDD
        $articles = $articleRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }

    #[Route('/article/{id}', name: 'show_article')]
    public function getArticleInfo(int $id, ArticleRepository $articleRepository): Response
    {
        // récupérer en bdd les informations liées à l'article avec l'id = {id}
        $article = $articleRepository->find($id);
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/category/{id_category}', name: 'app_articles_by_category')]
    public function getArticlesByCategory(int $id_category, ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        // récupérer en bdd les informations liées à l'article avec l'id = {id}
        $articles = $articleRepository->findBy(['category' => $id_category]);
        $categories = $categoryRepository->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories
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


