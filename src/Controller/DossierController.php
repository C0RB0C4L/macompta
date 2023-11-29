<?php

namespace App\Controller;

use App\Repository\DossierRepository;
use App\Service\FlashService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dossier', name: 'app_dossier_')]
class DossierController extends AbstractController
{
    #[Route('/{uuid}', name: 'detail')]
    public function details(string $uuid, DossierRepository $repo): Response
    {
        // @todo 
        // $ecritures = $repo->selectEcrituresFromDossier($uuid):
        /*
        if ($ecritures !== null) {
            $this->addFlash(FlashService::TYPE_WARNING, "Le dossier voulu n'existe pas.");
            
            return $this->redirectToRoute("app_home");
        }
        */

        return $this->render("dossier/detail.html.twig", []);
    }
}
