<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Form\DossierForm;
use App\Repository\DossierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/', name: 'app_')]
class HomeController extends AbstractController
{
    #[Route('', name: 'home')]
    public function home(Request $request, DossierRepository $repo): Response
    {
        return $this->render("home/index.html.twig", []);
    }
}
