<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ClubRepository;


class ClubController extends AbstractController
{
    #[Route('/club')]
    public function getClub(ClubRepository $clubRepository): Response
    {
        $club = $clubRepository->findAll();

        dd($club);

        return new Response("Test");
    }
}
