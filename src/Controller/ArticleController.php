<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $articleRepository, 
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator,
        Request $request): Response
    {

        // je récupère le paramètre get sort
        $sort = $request->query->get('sort', 'price_asc');

        $sortOptions = [
            'price_asc' => ['a.price', 'ASC'],
            'price_desc' => ['a.price', 'DESC'],
            'date_asc' => ['a.date', 'ASC'],
            'date_desc' => ['a.date', 'DESC'],
        ];

        $sortField = $sortOptions[$sort][0] ?? 'a.price';
        $sortOrder = $sortOptions[$sort][1] ?? 'ASC';

        // ArticleRepository $articleRepositor => injecter le repo de Article
        // et j'ai appelé une méthode prédéfinie de la couche ServiceEntityRepository qui me permet de récupérer
        // tous les articles en BDD
        $categories = $categoryRepository->findAll();

        $pagination = $paginator->paginate(
            //$articleRepository->findAll(), /* query NOT result */
            $articleRepository->findSortByOrder($sortField, $sortOrder),
            $request->query->getInt('page', 1), /* page number */
            5 /* limit per page */
        );

        return $this->render('article/index.html.twig', [
            'articles' => $pagination,
            'categories' => $categories,
            'sort' => $sort
        ]);
    }

    #[Route('/article/{id}', name: 'show_article')]
    public function getArticleInfo(int $id, ArticleRepository $articleRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // récupérer en bdd les informations liées à l'article avec l'id = {id}
        $article = $articleRepository->find($id);

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $review->setArticle($article);
            $review->setUser($this->getUser());
            $review->setCreateAt(new \DateTime());
    
            $entityManager->persist($review);
            $entityManager->flush();
    
            return $this->redirectToRoute('show_article', ['id' => $article->getId()]);
        }


        return $this->render('article/show.html.twig', [
            'formReview' => $form,
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

    #[Route('/article/{id}/json', name: 'get_article_json')]
    public function getArticleJson(int $id, ArticleRepository $articleRepository): JsonResponse
    {
        // récupérer en bdd les informations liées à l'article avec l'id = {id}
        $article = $articleRepository->find($id);

        if(!$article) {
            return $this->json(['error' => 'Article non trouvé !'], 404);
            // ça permet de renvoyer une erreur 404 si aucun produit n'est trouvé en BDD
        }

        return $this->json([
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'description' => $article->getDescription(),
            'price' => $article->getPrice(),
            'stock' => $article->getStock(),
            'category' => $article->getCategory()->getName(),
            'image' => $article->getPicture(),
        ]);

    }



}


