<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Status;
use App\Entity\Society;
use App\Entity\DeveloperView;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

use App\Form\SocietyCompleteProfilType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Developer;
use App\Form\Dev\ModifyPassword;
use Symfony\Component\Form\FormError;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SocietyController extends AbstractController
{
    #[Route('/society', name: 'app_society')]
    public function index(): Response
    {
        return $this->dashboard_society();
    }

    #[Route('/society/dashboard', name: 'app_society_dash')]
    #[IsGranted('ROLE_SOCIETY')]
    public function dashboard_society(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if($user->isActive() == false){
           return $this->redirectToRoute('app_society_complete_profil');
        }
        
        $popular_developers = $userRepository->popularDevs();
        $last_developers = $userRepository->findLastCreatedDevs(3);

        return $this->render('society/dashboard.html.twig',[
            'popular_developers' => $popular_developers,
            'last_developers' => $last_developers,
        ]);
    }
    

    #[Route('/society/complete-profil', name: 'app_society_complete_profil', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_SOCIETY')]
    public function complete_profil_society(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
        
        // Vérifiez si l'utilisateur a un profil développeur
        $society = $user->getSociety();

        if (!$society) {
            // Créez un nouveau profil développeur si nécessaire
            $society = new Society();
            $society->setUser($user);
            $user->setSociety($society);
            $entityManager->persist($society);
        }

        // Créez le formulaire pour le développeur
        $form = $this->createForm(SocietyCompleteProfilType::class, $society);
        $form->handleRequest($request); // && $form->isValid()

        if ($form->isSubmitted() && $form->isValid() ) {
           
            // Gestion de l'avatar (si le champ avatar existe dans le formulaire)
            $avatarFile = $form->get('avatar')->getData();
    
            if ($avatarFile) {
                $newFilename = uniqid() . '.' . $avatarFile->guessExtension();
    
                $avatarFile->move(
                    $this->getParameter('avatars_directory'), // Configurez ce paramètre
                    $newFilename
                );
                $society->setAvatar($newFilename);
            }
    
            $user->setIsActive(true);
            // Enregistrez les modifications dans la base de données
            $entityManager->flush(); 
    
            $this->addFlash('success', 'Votre profil société a été mis à jour avec succès.');
    
            return $this->redirectToRoute('app_society_dash'); 
        }

        
        return $this->render('society/complete-profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /** Profil d'un dev */
    #[Route('/society/developer/{id}', name: 'app_society_developer')]
    public function show_dev(Developer $developer, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $society = $user->getSociety();

        // Vérifiez si une vue existe déjà
        $existingView = $entityManager->getRepository(DeveloperView::class)->findOneBy([
            'developer' => $developer,
            'society' => $society,
        ]);

        if (!$existingView) {
            // Créez une nouvelle vue si elle n'existe pas
            $view = new DeveloperView();
            $view->setDeveloper($developer);
            $view->setSociety($society);
    
            $entityManager->persist($view);
            $entityManager->flush();
        }

        return $this->render('society/show-dev.html.twig', [
            'developer' => $developer,
        ]);
    }

    /** Les devs qui ont postulés à mes offres */

    #[Route('/society/candidatures', name: 'society_candidatures_list')]
    public function listCandidatures(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        $society = $user->getSociety();

        if (!$society) {
            $this->addFlash('error', 'Vous devez être une société pour accéder à cette page.');
            return $this->redirectToRoute('home');
        }

        $query = $entityManager->createQueryBuilder()
            ->select('c')
            ->from(Candidat::class, 'c')
            ->join('c.jobPosting', 'j')
            ->where('j.society = :society')
            ->setParameter('society', $society)
            ->orderBy('c.id', 'DESC')
            ->getQuery();

        $candidatures = $paginator->paginate($query,$request->query->getInt('page', 1), 10 );

        return $this->render('society/candidatures_list.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

    #[Route('/society/candidature/{id}/accept', name: 'society_candidature_accept', methods: ['POST'])]
    public function acceptCandidature(int $id, EntityManagerInterface $entityManager): Response
    {
        return $this->updateCandidatureStatus($id, 2, $entityManager);
    }

    #[Route('/society/candidature/{id}/reject', name: 'society_candidature_reject', methods: ['POST'])]
    public function rejectCandidature(int $id, EntityManagerInterface $entityManager): Response
    {
        return $this->updateCandidatureStatus($id, 3, $entityManager);
    }

    private function updateCandidatureStatus(int $id, int $newStatus, EntityManagerInterface $entityManager): Response
    {
        $candidature = $entityManager->getRepository(Candidat::class)->find($id);

        if (!$candidature) {
            throw $this->createNotFoundException('Candidature introuvable.');
        }

        $user = $this->getUser();
        $society = $user->getSociety();

        if (!$society || $candidature->getJobPosting()->getSociety() !== $society) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier cette candidature.');
            return $this->redirectToRoute('society_candidatures_list');
        }

        $status = $entityManager->getRepository(Status::class)->find( $newStatus );
        if (!$status) {
            throw new \Exception(sprintf('Le statut "%s" n\'existe pas.', $newStatus));
        }

        // Mettre à jour le statut de la candidature sélectionnée
        $candidature->setStatus($status);
        $entityManager->persist($candidature);

        // Si la candidature est acceptée, rejeter toutes les autres candidatures pour le même job
        if ($newStatus === 1) {
            $rejectedStatus = $entityManager->getRepository(Status::class)->find(3);
            if (!$rejectedStatus) {
                throw new \Exception('Le statut "rejeté" n\'existe pas.');
            }

            $qb = $entityManager->createQueryBuilder()
                ->update(Candidat::class, 'c')
                ->set('c.status', ':rejectedStatus')
                ->where('c.jobPosting = :job')
                ->andWhere('c.id != :acceptedId')
                ->setParameter('rejectedStatus', $rejectedStatus)
                ->setParameter('job', $candidature->getJobPosting())
                ->setParameter('acceptedId', $candidature->getId());

            $qb->getQuery()->execute();
        }

        $entityManager->flush();

        $this->addFlash('success', sprintf('La candidature a été %s avec succès.', $newStatus));
        return $this->redirectToRoute('society_candidatures_list');
    }


    #[Route('/society/developer/{id}/add-favorite', name: 'society_add_favorite', methods: ['POST'])]
    public function addFavoriteDeveloper(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $society = $user->getSociety();

        if (!$society) {
            $this->addFlash('error', 'Vous devez être une société pour effectuer cette action.');
            return $this->redirectToRoute('home');
        }

        $developer = $entityManager->getRepository(Developer::class)->find($id);

        if (!$developer) {
            throw $this->createNotFoundException('Développeur introuvable.');
        }

        $society->addFavoriteDeveloper($developer);
        $entityManager->flush();

        $this->addFlash('success', 'Le développeur a été ajouté à vos favoris.');

        return $this->redirectToRoute('app_society_developer',['id' => $id]); // Redirection à personnaliser
    }

    #[Route('/society/developer/{id}/remove-favorite', name: 'society_remove_favorite', methods: ['POST'])]
    public function removeFavoriteDeveloper(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $society = $user->getSociety();

        if (!$society) {
            $this->addFlash('error', 'Vous devez être une société pour effectuer cette action.');
            return $this->redirectToRoute('home');
        }

        $developer = $entityManager->getRepository(Developer::class)->find($id);

        if (!$developer) {
            throw $this->createNotFoundException('Développeur introuvable.');
        }

        $society->removeFavoriteDeveloper($developer);
        $entityManager->flush();

        $this->addFlash('success', 'Le développeur a été retiré de vos favoris.');
         return $this->redirectToRoute('app_society_developer',['id' => $id]); // Redirection à personnaliser
    }


    #[Route('/society/dev-favorites', name: 'society_favorites_list')]
    public function listFavoriteDevelopers(): Response
    {
        $user = $this->getUser();
        $society = $user->getSociety();

        if (!$society) {
            throw $this->createAccessDeniedException('Vous devez être connecté en tant que société pour accéder à cette page.');
        }

        $favoriteDevelopers = $society->getFavoriteDevelopers();

        return $this->render('society/favorites_list_dev.html.twig', [
            'developers' => $favoriteDevelopers,
        ]);
    }

    #[Route('/company_change_password', name: 'company_change_password', methods: ['GET', 'POST'])]
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
                return $this->redirectToRoute('app_society_dash', [], Response::HTTP_SEE_OTHER); 
            }
        }
        return $this->render('society/employer-change-password.html.twig', [
           /*  'user' => $user,*/
            'form' => $form,
            'active_tab' => 'password',
            
        ]);
    }



}
