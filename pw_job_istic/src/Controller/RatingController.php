<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RatingController extends AbstractController
{
    #[Route('/rating', name: 'app_rating')]
    public function index(): Response
    {
        return $this->render('rating/index.html.twig', [
            'controller_name' => 'RatingController',
        ]);
    }

    #[Route('/rate/{id}', name: 'app_rate_dev')]
    public function rate(  User $ratedDev, Request $request,   EntityManagerInterface $entityManager ): Response { $currentUser = $this->getUser();

        if (!$currentUser) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour noter un développeur.');
        }

        $rating = new Rating();
        $rating->setRatedDev($ratedDev);
        $rating->setRatedBy($currentUser);
        $rating->setCreatedAt(new \DateTime());

        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rating);
            $entityManager->flush();

            $this->addFlash('success', 'Votre note a été enregistrée.');
            return $this->redirectToRoute('app_dev_list'); // Rediriger après la notation
        }

        return $this->render('rating/rate.html.twig', [
            'form' => $form->createView(),
            'ratedDev' => $ratedDev,
        ]);
    }
}
