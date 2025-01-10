<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\Dev\CompleteProfilType;
use App\Form\RatingType;
use App\Form\CandidatType;
use App\Form\Dev\ModifyPassword;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\DeveloperRepository;
use App\Repository\RatingRepository;
use App\Entity\User;
use App\Entity\Rating;
use App\Entity\Society;
use App\Entity\Developer;
use App\Entity\JobPosting;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Candidat;
use App\Entity\Status;
use App\Entity\JobView;
use Symfony\Component\Form\FormError;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeveloperController extends AbstractController
{
    #[Route('/dev', name: 'app_developper')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_dev_dash');
    }

    #[Route('/dev/dashboard', name: 'app_dev_dash')]
    #[IsGranted('ROLE_DEV')]
    public function dashboard_developper(EntityManagerInterface $entityManager, RatingRepository $ratingRepository): Response
    {

        $user = $this->getUser();
        if($user->isActive() == false){
            return $this->redirectToRoute('app_dev_complete_profil');
        }

        $developer = $user->getDeveloper();

        $latestJobs = $entityManager->getRepository(JobPosting::class)->findBy(
            [], // Pas de critère spécifique
            ['publishedAt' => 'DESC'], 3
        );

        $popularJobs = $entityManager->getRepository(JobPosting::class)->findMostPopularJobs(3);
        
        $candidatures = $entityManager->getRepository(Candidat::class)->findBy(
            ['developer' => $developer],
            ['id' => 'DESC']
        );

        $averageRating = $ratingRepository->getAverageRatingForDeveloper($user->getId());

        return $this->render('developer/dashboard.html.twig',[
            'last_jobs' => $latestJobs,
            'popular_jobs'=> $popularJobs ,
            'nbre_candidature' => count($candidatures),
            'active_tab' => 'dashboard',
            'ma_note' => $averageRating,
        ]);
    }


    #[Route('/dev/complete-profil', name: 'app_dev_complete_profil', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_DEV')]
    public function complete_profil_developper(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }

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
            'active_tab' => 'profil',
        ]);
    }



    #[Route('/developer-list', name: 'list_developer')]
    public function developer_list(DeveloperRepository  $developers): Response
    {
        $developers = $developers->findActiveDevelopers();
        return $this->render(
            'developer/developer_list.html.twig', [
            'developers' => $developers,
            'nbrDev' => count($developers),
        ]);
    }

    #[Route('/developer/{id}', name: 'app_developer')]
    public function show_dev(Developer $developer, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = null;

        if ($this->getUser()) {

            $user = $this->getUser();
           //$developer = $user->getDeveloper();
            
            $existingRating = $entityManager->getRepository(Rating::class)->findOneBy([
                'ratedDev' => $developer->getUser(), // L'utilisateur associé au développeur
                'ratedBy' => $this->getUser(), // L'utilisateur connecté
            ]);

            if ($existingRating){
                return $this->render('developer/show-dev.html.twig', [
                    'developer' => $developer,
                    'form' =>  null,
                    'existingRating' => $existingRating,
                ]);
            }else{
                $rating = new Rating();
                $rating->setRatedDev( $developer->getUser() );
                $rating->setRatedBy($this->getUser());
                $rating->setCreatedAt(new \DateTime());

                $form = $this->createForm(RatingType::class, $rating);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($rating);
                    $entityManager->flush();
        
                    $this->addFlash('success', 'Votre note a été enregistrée.');
        
                    return $this->redirectToRoute('app_developer', ['id' => $developer->getId()]);
                }
            }
            
        }

        return $this->render('developer/show-dev.html.twig', [
            'developer' => $developer,
            'form' => $form ? $form->createView() : null,
            'existingRating' => null
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
            'active_tab' => 'job',
            'nbJobs' => $jobs->getTotalItemCount(),
        ]);
    }


    #[Route('/job/{id}', name: 'dev_job_details', requirements: ['id' => '\d+'])]
    public function jobDetails(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $job = $entityManager->getRepository(JobPosting::class)->find($id);

        if (!$job) {
            throw $this->createNotFoundException('Le job demandé n\'existe pas.');
        }

        $user = $this->getUser();
        $developer = $user->getDeveloper();

        if (!$developer) {
            $this->addFlash('error', 'Vous devez être un développeur pour voir cette page.');
            return $this->redirectToRoute('home');
        }

        // Vérifier si le développeur a déjà vu ce job
        $existingView = $entityManager->getRepository(JobView::class)->findOneBy([
            'job' => $job,
            'developer' => $developer,
        ]);
        if (!$existingView) {
            // Créer une nouvelle vue si elle n'existe pas
            $jobView = new JobView();
            $jobView->setJob($job);
            $jobView->setDeveloper($developer);
    
            $entityManager->persist($jobView);
            $entityManager->flush();
        }


        $candidature = $entityManager->getRepository(Candidat::class)->findOneBy([
            'developer' => $developer,
            'jobPosting' => $job,
        ]);

        $candidature_bool = $candidature ? true : false;
        
        $form = null;
        if ($candidature_bool === false) {
            $candidature = new Candidat();
            $form = $this->createForm(CandidatType::class, $candidature, [
                'action' => $this->generateUrl('dev_job_apply', ['id' => $id]),
                'method' => 'POST',
            ]);
        }

        return $this->render('developer/jobs/details.html.twig', [
            'job' => $job,
            'form' => $form ? $form->createView() : null,
            'candidature' => $candidature ? $candidature : null,
            'candidature_bool'=> $candidature_bool
        ]);
    }


    /** Psotuler à une offre */
    #[Route('/job/{id}/apply', name: 'dev_job_apply', methods: ['POST'])]
    public function saveCandidature(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $job = $entityManager->getRepository(JobPosting::class)->find($id);

        if (!$job) {
            throw $this->createNotFoundException('Le job demandé n\'existe pas.');
        }

        $user = $this->getUser();
        $developer = $user->getDeveloper();

        if (!$developer) {
            $this->addFlash('error', 'Vous devez être un développeur pour postuler.');
            return $this->redirectToRoute('dev_job_details', ['id' => $id]);
        }

        // Vérifiez si une candidature existe déjà
        $existingCandidature = $entityManager->getRepository(Candidat::class)->findOneBy([
            'developer' => $developer,
            'jobPosting' => $job,
        ]);

        if ($existingCandidature) {
            $this->addFlash('info', 'Vous avez déjà postulé à cette offre.');
            return $this->redirectToRoute('dev_job_details', ['id' => $id]);
        }

        // Récupérez le statut "en attente"
        $defaultStatus = $entityManager->getRepository(Status::class)->find(1);

        if (!$defaultStatus) {
            throw new \Exception('Le statut "en attente" n\'existe pas. Veuillez le créer dans la table Status.');
        }

        // Créez une nouvelle candidature
        $candidature = new Candidat();
        $candidature->setDeveloper($developer);
        $candidature->setJobPosting($job);
        $candidature->setStatus($defaultStatus);

        $form = $this->createForm(CandidatType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidature);
            $entityManager->flush();

            $this->addFlash('success', 'Votre candidature a été envoyée avec succès.');
            return $this->redirectToRoute('dev_job_details', ['id' => $id]);
        }

        return $this->render('developer/jobs/details.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
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
            'salaryRangeMin' => 0,
            'salaryRangeMax' => $developer->getSalary(),
            'location' => $developer->getLocalisation(),
            'experienceLevel' => 5, // $developer->getExperienceLevel(),
        ];

        // Construire une requête pour rechercher les postes correspondants
        $qb = $entityManager->getRepository(JobPosting::class)->createQueryBuilder('j');

        // Filtrer par technologies
        if (!empty($criteria['technologies'])) {
            $qb->join('j.technologies', 't')
            ->orWhere('t.name IN (:technologies)')
            ->setParameter('technologies', $criteria['technologies']);
        }

        // Filtrer par localisation
        if (!empty($criteria['location'])) {
            $qb->orWhere('j.location = :location')
            ->setParameter('location', $criteria['location']);
        }

        // Filtrer par niveau d'expérience
        if (!empty($criteria['salaryRange'])) {
            $qb->orWhere('j.salary BETWEEN :minSalary AND :maxSalary')
            ->setParameter('minSalary', $criteria['salaryRangeMin'])
            ->setParameter('maxSalary', $criteria['salaryRangeMax']);
        }

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
            'nbMatching' => $pagination->getTotalItemCount(),
            'active_tab' => 'matching',
        ]);
    }

    /** Ajouter une offre aux favoris */
    #[Route('/job/{id}/favorite', name: 'dev_job_favorite_add', methods: ['POST'])]
    public function addFavoriteJob(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $developer = $user->getDeveloper();

        if (!$developer) {
            $this->addFlash('error', 'Vous devez être un développeur pour ajouter un job à vos favoris.');
            return $this->redirectToRoute('dev_job_details', ['id' => $id]);
        }

        $jobPosting = $entityManager->getRepository(JobPosting::class)->find($id);

        if (!$jobPosting) {
            throw $this->createNotFoundException('Le job demandé n\'existe pas.');
        }

        if ($developer->getFavoriteJobs()->contains($jobPosting)) {
            $this->addFlash('info', 'Ce job est déjà dans vos favoris.');
        } else {
            $developer->addFavoriteJob($jobPosting);
            $entityManager->flush();
            $this->addFlash('success', 'Job ajouté à vos favoris avec succès.');
        }

        return $this->redirectToRoute('dev_job_details', ['id' => $id]);
    }


    /** Retirer de la liste des favories */
    #[Route('/job/{id}/unfavorite', name: 'dev_job_favorite_remove', methods: ['POST'])]
    public function removeFavoriteJob(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $developer = $user->getDeveloper();

        if (!$developer) {
            $this->addFlash('error', 'Vous devez être un développeur pour retirer un job de vos favoris.');
            return $this->redirectToRoute('dev_job_details', ['id' => $id]);
        }

        $jobPosting = $entityManager->getRepository(JobPosting::class)->find($id);

        if (!$jobPosting) {
            throw $this->createNotFoundException('Le job demandé n\'existe pas.');
        }

        if ($developer->getFavoriteJobs()->contains($jobPosting)) {
            $developer->removeFavoriteJob($jobPosting);
            $entityManager->flush();
            $this->addFlash('success', 'Job retiré de vos favoris avec succès.');
        } else {
            $this->addFlash('info', 'Ce job n\'est pas dans vos favoris.');
        }

        return $this->redirectToRoute('dev_job_details', ['id' => $id]);
    }


    /**Mes jobs favoris */
    #[Route('/favorites', name: 'developer_favorites_jobs')]
    public function listFavorites(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $developer = $user->getDeveloper();

        if (!$developer) {
            $this->addFlash('error', 'Vous devez être un développeur pour accéder à vos favoris.');
            return $this->redirectToRoute('home');
        }

        $favoriteJobs = $developer->getFavoriteJobs();

        return $this->render('developer/jobs/favorites_job.html.twig', [
            'jobs' => $favoriteJobs,
            'active_tab' => 'jobFav',
        ]);
    }



    /** Liste des offres auxquelles j'ai postulé */
    #[Route('/mes-candidatures', name: 'dev_candidatures_list')]
    public function listCandidatures(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        $developer = $user->getDeveloper();

        if (!$developer) {
            $this->addFlash('error', 'Vous devez être un développeur pour accéder à cette page.');
            return $this->redirectToRoute('home');
        }

        // Récupérer les candidatures par ordre décroissant
        $candidatures = $entityManager->getRepository(Candidat::class)->findBy(
            ['developer' => $developer],
            ['id' => 'DESC']
        );

        // Rendre la vue avec les candidatures
        return $this->render('developer/candidatures_list.html.twig', [
            'candidatures' => $candidatures,
            'active_tab' => 'candidatures',
        ]);
    }


    // #[Route('/dev/my_resume', name: 'app_dev_resume')]
    // #[IsGranted('ROLE_DEV')]
    // public function resume_developper(EntityManagerInterface $entityManager): Response
    // {

    //     $user = $this->getUser();
    //     if($user->isActive() == false){
    //         return $this->redirectToRoute('app_dev_complete_profil');
    //     }

    //     $developer = $user->getDeveloper();

    //     return $this->render('developer/my_resume.html.twig',[
    //         'dev' => $developer,
    //         'active_tab' => 'resume',
    //     ]);
    // }


    #[Route('/candidat_change_password', name: 'candidat_change_password', methods: ['GET', 'POST'])]
    public function modify(Request $request, EntityManagerInterface $entityManager,  UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $user = $this->getUser();
        $form = $this->createForm(ModifyPassword::class, $user);
        $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
        
            $oldPassword = $form->get('password')->getData();
            $newPassword = $form->get('newPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            // Vérifier si le mot de passe actuel est correct
            if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
                $form->get('password')->addError(new FormError('Ancien mot de passe incorrect.'));
            }

            // Vérifier si le nouveau mot de passe et la confirmation correspondent
            if ($newPassword !== $confirmPassword) {
                $form->get('confirmPassword')->addError(new FormError('Les mots de passe ne correspondent pas.'));
            }

            // Si tout est valide, mettre à jour le mot de passe
            if ($form->isValid()) {
             
                $encodedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($encodedPassword);
                // Sauvegarder les modifications dans la base de données
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');

                // Rediriger vers une page de profil ou une autre page
                return $this->redirectToRoute('app_dev_dash', [], Response::HTTP_SEE_OTHER); 
            }
        }
        return $this->render('developer/jobs/candidate_change_password.html.twig', [
           /*  'user' => $user,*/
            'form' => $form,
            'active_tab' => 'password',
            
        ]);
    }

}
