<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Society;
use App\Entity\Developer;
use App\Form\UserType;
use App\Form\UserTypeSociety;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    

    #[Route('/sign-in-dev', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,  UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $user = new User();
        //Les devs
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_DEV']);

            $entityManager->persist($user);
            $entityManager->flush();

            $developer = new Developer();
            $developer->setUser($user);
            $entityManager->persist($developer);
            $entityManager->flush();

            return $this->redirectToRoute('app_dev_complete_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/sign-in-society', name: 'app_user_new_society', methods: ['GET', 'POST'])]
    public function new_society(Request $request, EntityManagerInterface $entityManager,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        //Les devs
        $form = $this->createForm(UserTypeSociety::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_SOCIETY']);
            $entityManager->persist($user);
            $entityManager->flush();


            // Associer un utilisateur à une société
            $society = new Society();
            $society->setUser($user);
            $entityManager->persist($society);
            $entityManager->flush();


            return $this->redirectToRoute('app_society_complete_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new_society.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


 #[Route('/{id}', name: 'app_user_update', methods: ['GET', 'POST'])]
    public function update(
    Request $request, 
    EntityManagerInterface $entityManager,  
    UserPasswordHasherInterface $passwordHasher,
    int $id // L'ID de l'utilisateur à modifier
    ): Response
{
    // Récupérer l'utilisateur existant à partir de la base de données
    $user = $entityManager->getRepository(User::class)->find($id);

    if (!$user) {
        // Si l'utilisateur n'existe pas, afficher une erreur
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    // Créer le formulaire pour modifier le mot de passe
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        // Assurez-vous que le mot de passe n'est pas vide
        $newPassword = $user->getPassword();
        if (empty($newPassword)) {
            $this->addFlash('error', 'Le mot de passe ne peut pas être vide.');
            return $this->redirectToRoute('app_user_update', ['id' => $user->getId()]);
        }

        // Hacher le nouveau mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);

        try {
            // Persister et enregistrer l'utilisateur avec le mot de passe modifié
            $entityManager->flush();

            // Optionnel : ajouter un message flash pour informer l'utilisateur
            $this->addFlash('success', 'Votre mot de passe a été mis à jour avec succès.');

            // Rediriger vers une page de confirmation ou de profil
            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);

        } catch (\Exception $e) {
            // Gérer l'exception si quelque chose échoue
            $this->addFlash('error', 'Une erreur est survenue lors de la mise à jour de votre mot de passe.');
        }
    }

    // Rendre le formulaire
    return $this->render('user/update.html.twig', [
        'form' => $form->createView(),
    ]);
}




}