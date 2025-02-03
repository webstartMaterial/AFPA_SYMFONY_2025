<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {


        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted()) {

            if($form->isValid()) {

                $contact->setDate(new \DateTime());
                $entityManager->persist($contact); // j'enregistre l'objet contact en BDD
                $entityManager->flush(); // ferme la transaction en cours

                $this->addFlash(
                    'success',
                    'Votre message a bien été envoyé'
                );

                // TODO : envoyer un email

                // Redirection vers la même route pour éviter une double soumission
                 return $this->redirectToRoute('app_contact');

            }

        }


        return $this->render('contact/index.html.twig', [
            'contactForm' => $form,
        ]);
    }
}
