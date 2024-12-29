<?php

namespace App\Controller;

use App\Entity\JobPosting;
use App\Form\JobPostingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class JobPostingController extends AbstractController
{
    #[Route('/society/job/new', name: 'society_job_posting_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jobPosting = new JobPosting();
        $form = $this->createForm(JobPostingType::class, $jobPosting);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des fichiers (image et PDF)
            $image = $form->get('image')->getData();
            $pdfFile = $form->get('pdfFile')->getData();

            
            if ($image) {
                // Générez un nom unique pour l'image
                $imageName = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('avatars_directory'), $imageName);
                $jobPosting->setImage($imageName);
            }

            if ($pdfFile) {
                // Générez un nom unique pour le fichier PDF
                $pdfName = uniqid() . '.' . $pdfFile->guessExtension();
                $pdfFile->move($this->getParameter('avatars_directory'), $pdfName);
                $jobPosting->setPdfFile($pdfName);
            }

            
            // Associer les technologies
            $technologies = $form->get('technologies')->getData(); // Doctrine retourne une collection
            foreach ($technologies as $technology) {
                $jobPosting->addTechnology($technology); // Utilise addTechnology de l'entité
            }

            // Associer la société via l'utilisateur connecté (si applicable)
            if ($this->getUser() && method_exists($this->getUser(), 'getSociety')) {
                $society = $this->getUser()->getSociety();
                if ($society) {
                    $jobPosting->setSociety($society);
                }
            }
            // Persist et flush
            $entityManager->persist($jobPosting);
            $entityManager->flush();

            // Message de succès et redirection
            $this->addFlash('success', 'Fiche de poste créée avec succès !');
            return $this->redirectToRoute('society_job_posting_list');
        }

        return $this->render('society/jobs/new_job.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/society/job/list', name: 'society_job_posting_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté et lié à une société
        if (!$user || !method_exists($user, 'getSociety') || !$user->getSociety()) {
            $this->addFlash('error', 'Vous devez être associé à une société pour voir vos fiches de poste.');
            //return $this->redirectToRoute('some_default_route');
        }

        // Récupérer la société de l'utilisateur
        $society = $user->getSociety();

        // Rechercher les fiches de poste liées à cette société
        $jobPostings = $entityManager->getRepository(JobPosting::class)->findBy(['society' => $society]);

        // Rendre la vue avec les fiches de poste
        return $this->render('society/jobs/list.html.twig', [
            'jobPostings' => $jobPostings,
        ]);
    }

    #[Route('/society/job/edit/{id}', name: 'job_posting_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $jobPosting = $entityManager->getRepository(JobPosting::class)->find($id);

        if (!$jobPosting || $jobPosting->getSociety() !== $this->getUser()->getSociety()) {
            throw $this->createNotFoundException('Fiche de poste introuvable ou accès non autorisé.');
        }

        $form = $this->createForm(JobPostingType::class, $jobPosting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Fiche de poste mise à jour avec succès.');
            return $this->redirectToRoute('job_posting_list');
        }

        return $this->render('society/jobs/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/society/job/delete/{id}', name: 'job_posting_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $jobPosting = $entityManager->getRepository(JobPosting::class)->find($id);

        if (!$jobPosting || $jobPosting->getSociety() !== $this->getUser()->getSociety()) {
            throw $this->createNotFoundException('Fiche de poste introuvable ou accès non autorisé.');
        }

        $entityManager->remove($jobPosting);
        $entityManager->flush();

        $this->addFlash('success', 'Fiche de poste supprimée avec succès.');
        return $this->redirectToRoute('job_posting_list');
    }


}
