<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{

    // si je vais sur https://127.0.0.1:8000/
    // j'afficherai le template de home/index.html.twig
    // en passant à la vue un paramètre controller_name dont la valeur est HomeController
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // ça me récupère tous les articles en BDD
        $articles = $entityManager->getRepository(Article::class)->findAll();

        // dd($articles);

        // je passse mes articles à la vue quand je veux rendre ma vue
        return $this->render('home/index.html.twig', [
            'articles' => $articles
        ]);
    }
}
