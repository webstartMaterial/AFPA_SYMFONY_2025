<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create("fr_FR");


        for ($i = 0; $i < 5; $i++) {

            //// CATEGORY
            $category = new Category();
            $category->setName($faker->word());
            $manager->persist($category);

            /// ARTICLE
            $article = new Article();
            $article->setCategory($category);
            $article->setTitle($faker->title);
            $article->setDescription($faker->text);
            $article->setPicture($faker->imageUrl);
            $article->setPrice($faker->numberBetween(10, 40));
            $article->setStock($faker->numberBetween(10, 40));
            $article->setCreatedAt($faker->dateTimeThisMonth());
            $manager->persist($article);

            /// USER
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setCity($faker->city);
            $user->setAddress($faker->address);
            $user->setPostalCode($faker->postcode);
            $user->setPhoneNumber("0000000000");
            // $user->setRoles([""]);
            $manager->persist($user);

            /// COMMANDE
            $order = new Order();
            $order->setAmount($faker->numberBetween(10, 40));
            $order->setDate(new \DateTime());
            $order->setStatus('En cours');
            $order->setInvoice("invoice1.pdf");
            $order->setUser($user);
            $manager->persist($order);

            /// DETAILS DE COMMANDES
            for ($j = 0; $j < 5; $j++) {
                $order_detail = new OrderDetails();
                $order_detail->setArticle($article);
                $order_detail->setQuantity($faker->numberBetween(1, 4));
                $order_detail->setRelatedOrder($order);
                $order_detail->setSubtotal($faker->numberBetween(10, 40));
                $manager->persist($order_detail);
            }

            /// CONTACT
            $contact = new Contact();
            $contact->setDate(new \DateTime());
            $contact->setFirstName($faker->firstName);
            $contact->setLastName($faker->lastName);
            $contact->setMessage($faker->text);
            $contact->setObject($faker->text);
            $contact->setEmail($faker->email);
            $manager->persist($contact);


            /// REVIEW
            for ($k = 0; $k < 5 + $i; $k++) {

                $review = new Review();
                $review->setArticle($article);
                $review->setComment($faker->text);
                $review->setUser($user);
                $review->setCreateAt($faker->dateTimeThisMonth());
                $manager->persist($review);

            }
        }


        $manager->flush();
    }
}
