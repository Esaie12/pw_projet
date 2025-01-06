<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\JobPosting;
use App\Form\JobFilterWelcomeType;
use App\Repository\JobPostingRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\JobFilterJobPageType;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        if($user){
            return $this->redirectDash();
        }

        $form = $this->createForm(JobFilterWelcomeType::class,
            [
                'method' => 'GET', // Soumettre les données en GET
                'action' => $this->generateUrl('app_jobs'), 
            ]
        );

        return $this->render('home.html.twig',[
            'filterForm' => $form->createView(),
        ]);
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

    
    #[Route('/jobs', name: 'app_jobs')]
    public function appJobs(Request $request, JobPostingRepository $jobPostingRepository, PaginatorInterface $paginator,): Response
    {
        // Récupérer les critères depuis les paramètres GET
        $criteria = $request->query->all();

        if (!empty($criteria)) {
            $qb = $jobPostingRepository->findByFilters($criteria);
           
        } else {
            $qb = $jobPostingRepository->createQueryBuilder('job')
            ->orderBy('job.publishedAt', 'DESC');
        }


        $pagination = $paginator->paginate(
            $qb->getQuery(), 
            $request->query->getInt('page', 1),
            10 
        );


        $form = $this->createForm(JobFilterJobPageType::class,
            [
                'method' => 'GET', // Soumettre les données en GET
                'action' => $this->generateUrl('app_jobs'), 
            ]
        );

        return $this->render('all_jobs.html.twig', [
            'jobs' => $pagination, // Résultats des jobs
            'criteria' => $criteria, // Pour afficher les critères éventuellement
            'filterForm' => $form->createView(),
        ]);
    }

    

    
    


}
