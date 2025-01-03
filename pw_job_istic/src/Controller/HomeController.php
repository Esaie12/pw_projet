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
        $user = $this->getUser();
        if($user){
            return $this->redirectDash();
        }
         return $this->render('home.html.twig',[]);
    }


    #[Route('/redirect-after-login', name: 'app_redirect')]
    public function redirectDash(): Response
    {
        $user = $this->getUser();
        if ($user) {
            if ($user->getTypeUser() === 'dev') {
                return $this->redirectToRoute('app_dev_dash');
            } elseif ($user->getTypeUser() === 'society') {
                return $this->redirectToRoute('app_society_dash');
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    
    

    
    


}
