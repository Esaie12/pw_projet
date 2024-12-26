<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home.html.twig',[]);
    }

    
    #[Route('/dev/dashboard', name: 'app_dev_dash')]
    #[IsGranted('ROLE_DEV')]
    public function dashboard_developper(): Response
    {
        dd("Dash dev");
        return $this->render('home.html.twig',[]);
    }

    
    #[Route('/society/dashboard', name: 'app_society_dash')]
    #[IsGranted('ROLE_SOCIETY')]
    public function dashboard_society(): Response
    {
        dd("Dash sociét");
        return $this->render('home.html.twig',[]);
    }


}
