<?php

namespace App\Controller;

use App\Entity\Society;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SocietyController extends AbstractController
{
    #[Route('/society/{id}', name: 'app_society')]
    public function index(Society $society): Response
    {
        return $this->render('society/index.html.twig', [
            'society' => $society
        ]);
    }
}
