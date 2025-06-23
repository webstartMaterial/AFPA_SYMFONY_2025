<?php

namespace App\Tests\Repository; // Déclare l’espace de nom du test (doit refléter la structure de ton projet)

use App\Entity\Article;    // Importe l’entité Article à tester
use App\Entity\Category;   // Importe l’entité Category, utilisée par Article
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase; // Classe de base pour les tests d’intégration Symfony

class ArticleRepositoryTest extends KernelTestCase // Début de la classe de test
{
    public function testArticleIsPersistedAndRetrievedCorrectly(): void // Nom explicite du test : on vérifie la persistance et récupération d’un article
    {
        self::bootKernel(); // Démarre le kernel Symfony (comme un mini serveur pour tester les services)
        $container = static::getContainer(); // Récupère le conteneur de services Symfony
        $entityManager = $container->get('doctrine')->getManager(); // Récupère l’EntityManager de Doctrine (accès à la BDD)

        // ➕ Création d’une catégorie à associer à l’article (relation ManyToOne obligatoire)
        $category = new Category();
        $category->setName('Électronique'); // On donne un nom à la catégorie

        $entityManager->persist($category); // On prépare Doctrine à insérer cette catégorie en base

        // ➕ Création d’un article avec toutes ses propriétés
        $article = new Article();
        $article->setTitle('Smartphone XYZ'); // Titre de l’article
        $article->setCategory($category); // On associe la catégorie créée
        $article->setPicture('smartphone.jpg'); // Image
        $article->setDescription('Un smartphone performant'); // Description
        $article->setStock(20); // Stock disponible
        $article->setPrice('699.99'); // Prix au format string car stocké en DECIMAL
        $article->setCreatedAt(new \DateTimeImmutable()); // Date de création

        $entityManager->persist($article); // On prépare l’enregistrement en base
        $entityManager->flush(); // Envoie toutes les entités persistées dans la base (INSERT SQL)

        // 🔎 Récupération de l’article depuis la base de données par son ID
        $articleId = $article->getId(); // On récupère l’ID généré automatiquement
        $repo = $entityManager->getRepository(Article::class); // Récupère le repository de l’entité Article
        $savedArticle = $repo->find($articleId); // Va chercher l’article en base avec son ID

        // ✅ Vérifications (Assertions) : on s’assure que tout est bien sauvegardé

        $this->assertInstanceOf(Article::class, $savedArticle); // Vérifie que l’objet retourné est bien un Article
        $this->assertEquals('Smartphone XYZ', $savedArticle->getTitle()); // Le titre est bien celui attendu
        $this->assertEquals('smartphone.jpg', $savedArticle->getPicture()); // L’image correspond
        $this->assertEquals(20, $savedArticle->getStock()); // Le stock est exact
        $this->assertEquals('699.99', $savedArticle->getPrice()); // Le prix est le bon
        $this->assertEquals('Électronique', $savedArticle->getCategory()->getName()); // Le nom de la catégorie liée est correct
    }
}
