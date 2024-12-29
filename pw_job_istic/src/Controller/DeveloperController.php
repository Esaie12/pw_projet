<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\Dev\CompleteProfilType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\DeveloperRepository;
use App\Entity\User;
use App\Entity\Society;
use App\Entity\Developer;
use App\Entity\JobPosting;
use Knp\Component\Pager\PaginatorInterface;


class DeveloperController extends AbstractController
{
    #[Route('/dev', name: 'app_developper')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_dev_dash');
    }

    #[Route('/dev/dashboard', name: 'app_dev_dash')]
    #[IsGranted('ROLE_DEV')]
    public function dashboard_developper(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if($user->isActive() == false){
            return $this->redirectToRoute('app_dev_complete_profil');
        }

        $latestJobs = $entityManager->getRepository(JobPosting::class)->findBy(
            [], // Pas de critère spécifique
            ['publishedAt' => 'DESC'], // Trier par date de publication décroissante
            3 // Limiter à 3 résultats
        );

        return $this->render('developer/dashboard.html.twig',[
            'last_jobs' => $latestJobs,
        ]);
    }


    #[Route('/dev/complete-profil', name: 'app_dev_complete_profil', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_DEV')]
    public function complete_profil_developper(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $developer = $user->getDeveloper();

        if (!$developer) {
            $developer = new Developer();
            $developer->setUser($user);
            $user->setDeveloper($developer);
            $entityManager->persist($developer);
        }

        $form = $this->createForm(CompleteProfilType::class, $developer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement des langages
            $selectedLangages = $form->get('langages')->getData();
            foreach ($selectedLangages as $langage) {
                if (!$developer->getLangages()->contains($langage)) {
                    $developer->addLangage($langage);
                }
            }

            // Optionnel : Supprimer les langages non sélectionnés
            foreach ($developer->getLangages() as $existingLangage) {
                if (!$selectedLangages->contains($existingLangage)) {
                    $developer->removeLangage($existingLangage);
                }
            }

             // Gestion de l'avatar (si le champ avatar existe dans le formulaire)
             $avatarFile = $form->get('avatar')->getData();
    
             if ($avatarFile) {
                 $newFilename = uniqid() . '.' . $avatarFile->guessExtension();
     
                 $avatarFile->move(
                     $this->getParameter('avatars_directory'), // Configurez ce paramètre
                     $newFilename
                 );
                 $developer->setAvatar($newFilename);
             }
     
             $user->setIsActive(true);

            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès.');
            return $this->redirectToRoute('app_developper');
        }

        return $this->render('developer/complete-profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/developer-list', name: 'list_developer')]
    public function developer_list(DeveloperRepository  $developers): Response
    {
        return $this->render(
            'developer/developer_list.html.twig', [
            'developers' => $developers->findAll()
        ]);
    }

    #[Route('/developer/{id}', name: 'app_developer')]
    public function show_dev(Developer $developer): Response
    {
        return $this->render('developer/show-dev.html.twig', [
            'developer' => $developer,
        ]);
    }


    #[Route('/jobs/latest', name: 'dev_all_jobs')]
    public function listJobs(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $query = $entityManager->getRepository(JobPosting::class)->createQueryBuilder('j')
            ->orderBy('j.publishedAt', 'DESC')
            ->getQuery();

        $jobs = $paginator->paginate(
            $query, // Query pour récupérer les jobs
            $request->query->getInt('page', 1), // Numéro de la page
            10 // Nombre de résultats par page
        );

        return $this->render('developer/jobs/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    #[Route('/job/{id}', name: 'dev_job_details', requirements: ['id' => '\d+'])]
    public function jobDetails(int $id, EntityManagerInterface $entityManager): Response
    {
        // Rechercher le job par son ID
        $job = $entityManager->getRepository(JobPosting::class)->find($id);

        if (!$job) {
            throw $this->createNotFoundException('Le job demandé n\'existe pas.');
        }

        // Rendre la vue avec les détails du job
        return $this->render('developer/jobs/details.html.twig', [
            'job' => $job,
        ]);
    }

   
    #[Route('/dev/suggestions', name: 'app_dev_matching')]
    public function jobSuggestions(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifiez que l'utilisateur est un développeur
        $developer = $user->getDeveloper();

        if (!$developer) {
            $this->addFlash('error', 'Aucun profil développeur associé.');
            return $this->redirectToRoute('some_default_route'); // Redirigez si nécessaire
        }

        // Récupérer les critères du développeur
        $criteria = [
            'technologies' => $developer->getLangages()->map(fn($langage) => $langage->getName())->toArray(),
            'salaryRange' => $developer->getSalary(),
            'location' => $developer->getLocalisation(),
            'experienceLevel' => 5, // $developer->getExperienceLevel(),
        ];

        // Construire une requête pour rechercher les postes correspondants
        $qb = $entityManager->getRepository(JobPosting::class)->createQueryBuilder('j');

        // Filtrer par technologies
        /*if (!empty($criteria['technologies'])) {
            $qb->join('j.technologies', 't')
            ->andWhere('t.name IN (:technologies)')
            ->setParameter('technologies', $criteria['technologies']);
        }

        // Filtrer par localisation
        if (!empty($criteria['location'])) {
            $qb->andWhere('j.location = :location')
            ->setParameter('location', $criteria['location']);
        }

        // Filtrer par niveau d'expérience
        if (!empty($criteria['experienceLevel'])) {
            $qb->andWhere('j.experienceLevel = :experienceLevel')
            ->setParameter('experienceLevel', $criteria['experienceLevel']);
        }

        if (!empty($criteria['salaryRange'])) {
            $qb->andWhere('j.salary BETWEEN :minSalary AND :maxSalary')
            ->setParameter('minSalary', $criteria['salaryRange']['min'])
            ->setParameter('maxSalary', $criteria['salaryRange']['max']);
        }*/

        // Trier par date de publication
        $qb->orderBy('j.publishedAt', 'DESC');

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $qb->getQuery(), // Requête Doctrine
            $request->query->getInt('page', 1), // Numéro de la page (par défaut : 1)
            10 // Nombre d'éléments par page
        );

        return $this->render('developer/jobs/matchings.html.twig', [
            'jobs' => $pagination,
        ]);
    }


}
