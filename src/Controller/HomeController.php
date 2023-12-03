<?php

namespace App\Controller;

use App\Repository\EcritureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_')]
class HomeController extends AbstractController
{
    #[Route('', name: 'home')]
    public function home(EcritureRepository $repo): Response
    {
        $ecritures = $repo->selectAllEcritures();
        $ecrituresTotal = $repo->selectTotals();

        return $this->render("home/index.html.twig", [
            "ecritures" => $ecritures,
            "ecritures_total" => $ecrituresTotal
        ]);
    }
}
