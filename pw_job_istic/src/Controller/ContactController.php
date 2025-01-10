<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
        public function new(Request $request, EntityManagerInterface $entityManager): Response
        {
            $contact = new Contact();
    
            // Créer le formulaire
            $form = $this->createForm(ContactType::class, $contact);
    
            // Gérer la requête
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                // Sauvegarder les données
                $entityManager->persist($contact);
                $entityManager->flush();
    
                // Message de succès ou redirection
                $this->addFlash('success', 'Votre message a été envoyé avec succès !');
                return $this->redirectToRoute('contact_success'); // Redirigez vers une autre page si nécessaire
            }
    
            // Afficher le formulaire dans la vue
            return $this->render('contact/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
    
