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

class DeveloperController extends AbstractController
{
    #[Route('/dev', name: 'app_developper')]
    public function index(): Response
    {
        return $this->dashboard_developper();
    }

    #[Route('/dev/dashboard', name: 'app_dev_dash')]
    #[IsGranted('ROLE_DEV')]
    public function dashboard_developper(): Response
    {
        $user = $this->getUser();
        if($user->isActive() == false){
            return $this->redirectToRoute('app_dev_complete_profil');
        }
        return $this->render('developer/dashboard.html.twig',[]);
    }


    #[Route('/dev/complete-profil', name: 'app_dev_complete_profil', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_DEV')]
    public function complete_profil_developper(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Vérifiez si l'utilisateur a un profil développeur
        $developer = $user->getDeveloper();

        if (!$developer) {
            // Créez un nouveau profil développeur si nécessaire
            $developer = new Developer();
            $developer->setUser($user);
            $user->setDeveloper($developer);
            $entityManager->persist($developer);
        }

        
        // Créez le formulaire pour le développeur
        $form = $this->createForm(CompleteProfilType::class, $developer);
        $form->handleRequest($request); // && $form->isValid()

        if ($form->isSubmitted()) {
            
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
            // Enregistrez les modifications dans la base de données
            $entityManager->flush(); 
    
            $this->addFlash('success', 'Votre profil développeur a été mis à jour avec succès.');
    
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
}
