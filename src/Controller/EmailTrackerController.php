<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmailTrackerService;
use App\Repository\EmailTrackerRepository;

class EmailTrackerController extends AbstractController
{
    public function __construct(
        private EmailTrackerRepository $trackerRepository
    )
    {   
    }

    #[Route('/email/read/{token}.gif', name: 'email_tracker_read')]
    public function read(Request $request, EmailTrackerService $trackerService): Response
    {
        $token = $request->get('token');
        $trackerService->processEmailRead($token);

        $response = new Response(readfile('static/img/tracking.gif'));
        $response->headers->set('Content-Type', 'image/gif');
        return $response;
    }

    #[Route('/email/send/', name: 'email_tracker_send')]
    public function displayImage(): Response
    {
        $tracker = $this->trackerRepository->findOneById(1);
        return $this->render('email_tracker/index.html.twig', [
            'controller_name' => 'EmailTrackerController',
            'tracker' => $tracker
        ]);
    }
}
