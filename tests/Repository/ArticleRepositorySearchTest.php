<?php

namespace App\Tests\Repository; // Namespace des tests, reflète l’arborescence de l’application

use App\Entity\Article; // Entité principale testée
use App\Entity\Category; // Entité liée à Article
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase; // Classe de base pour les tests d’intégration avec Symfony

class ArticleRepositorySearchTest extends KernelTestCase // Test d’intégration du repository Article
{
    public function testFindArticlesBySearch(): void // Méthode de test : on vérifie que la recherche fonctionne
    {
        self::bootKernel(); // Démarre le kernel Symfony (comme si l’app tournait)
        $em = static::getContainer()->get('doctrine')->getManager(); // Récupère l’EntityManager pour interagir avec la BDD

        // 📂 Création d'une catégorie (obligatoire car la relation ManyToOne avec Article ne peut pas être null)
        $cat = new Category();
        $cat->setName('Divers');
        $em->persist($cat); // Préparation à l’insertion en base

        // ➕ Article 1 — contient "Ordinateur" dans le **titre** (doit être trouvé)
        $article1 = (new Article())
            ->setTitle('Ordinateur Portable')
            ->setDescription('Un modèle rapide')
            ->setCategory($cat)
            ->setPicture('pc.jpg')
            ->setStock(10)
            ->setPrice('800.00')
            ->setCreatedAt(new \DateTimeImmutable());
        $em->persist($article1);

        // ➕ Article 2 — contient "Ordinateur" dans la **description** (doit être trouvé aussi)
        $article2 = (new Article())
            ->setTitle('Smartphone')
            ->setDescription('Ordinateur de poche moderne')
            ->setCategory($cat)
            ->setPicture('phone.jpg')
            ->setStock(5)
            ->setPrice('600.00')
            ->setCreatedAt(new \DateTimeImmutable());
        $em->persist($article2);

        // ➖ Article 3 — ne contient pas "Ordinateur" (ne doit pas être trouvé)
        $article3 = (new Article())
            ->setTitle('Chaise ergonomique')
            ->setDescription('Mobilier de bureau confortable')
            ->setCategory($cat)
            ->setPicture('chair.jpg')
            ->setStock(15)
            ->setPrice('120.00')
            ->setCreatedAt(new \DateTimeImmutable());
        $em->persist($article3);

        $em->flush(); // Envoie toutes les entités persistées en base

        // 🔍 Appel de la méthode de recherche personnalisée avec le mot-clé "Ordinateur"
        $repo = $em->getRepository(Article::class);
        $results = $repo->findArticlesBySearch('Ordinateur');

        // ✅ Vérifications
        $this->assertCount(2, $results); // On doit trouver 2 articles seulement : article1 et article2

        // On récupère les titres des résultats pour les vérifier facilement
        $titles = array_map(fn($a) => $a->getTitle(), $results);

        // On vérifie que les bons articles sont présents
        $this->assertContains('Ordinateur Portable', $titles); // titre contient "Ordinateur"
        $this->assertContains('Smartphone', $titles); // description contient "Ordinateur"

        // Et que le mauvais article est bien exclu
        $this->assertNotContains('Chaise ergonomique', $titles); // Ne correspond pas au critère de recherche
    }
}
