<?php

namespace App\Controller;


use App\Entity\Society;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


use App\Form\SocietyCompleteProfilType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Developer;

class SocietyController extends AbstractController
{
    #[Route('/society', name: 'app_society')]
    public function index(): Response
    {
        return $this->dashboard_society();
    }

    #[Route('/society/dashboard', name: 'app_society_dash')]
    #[IsGranted('ROLE_SOCIETY')]
    public function dashboard_society(): Response
    {
        $user = $this->getUser();
        if($user->isActive() == false){
           //return $this->render('society/dashboard.html.twig',[]);
           return $this->redirectToRoute('app_society_complete_profil');
        }
        return $this->render('society/dashboard.html.twig',[]);
    }
    

    #[Route('/society/complete-profil', name: 'app_society_complete_profil', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_SOCIETY')]
    public function complete_profil_society(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

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

}
