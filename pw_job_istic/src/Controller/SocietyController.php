<?php

namespace App\Controller;


use App\Entity\Society;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


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
            return $this->render('society.complete-profil',[]);
        }
        return $this->render('home.html.twig',[]);
    }
    

}
