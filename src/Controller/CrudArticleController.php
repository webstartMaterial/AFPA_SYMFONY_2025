<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/crud/article')]
final class CrudArticleController extends AbstractController
{
    #[Route(name: 'app_crud_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('crud_article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_crud_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        //Request $request => intercepte les requêtes http pour cet url

        $article = new Article();

        // je créer un formulaire et je lis ce formulaire à l'instance de la classe Article
        // en gros, le formulaire que je vais afficher ou soumettre
        // est lié à mon objet article
        // si le formulaire n'a pas été soumis, $article est un objet vide
        // quand mon formulaire sera soumis, dans $article j'aurais la valeur des champs de mon formulaire
        // que j'aurais lié aux propriétés de l'objet $article
        $form = $this->createForm(ArticleType::class, $article);

        // je vais capter la requête lancer par mon formulaire
        $form->handleRequest($request);

        // si mon formulaire est valide est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // je pesiste mon objet $article en BDD (j'insert mon objet $article en bdd)
            $entityManager->persist($article);
            // je commit et ferme la transaction
            $entityManager->flush();

            // je fais redirection après la soumission de mon formulaire
            // vers la route app_crud_article_index
            return $this->redirectToRoute('app_crud_article_index', [], Response::HTTP_SEE_OTHER);
        }

        // j'affiche crud_article/new.html.twig
        // avec des paramètres de vue
        return $this->render('crud_article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('crud_article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_crud_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
