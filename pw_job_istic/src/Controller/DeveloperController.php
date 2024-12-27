<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Repository\DeveloperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeveloperController extends AbstractController
{
    #[Route('/developer/{id}', name: 'app_developer')]
    public function index(Developer $developer): Response
    {
        return $this->render('developer/index.html.twig', [
            'developer' => $developer,
        ]);
    }


    #[Route('/developer_list', name: 'list_developer')]
    public function developer_list(DeveloperRepository  $developers): Response
    {
        return $this->render('developer/developer_list.html.twig', [
                    'developers' => $developers->findAll()
                ]);
    }
}
