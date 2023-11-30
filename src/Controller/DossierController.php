<?php

namespace App\Controller;

use App\Form\DossierForm;
use App\Form\EcritureForm;
use App\Repository\DossierRepository;
use App\Repository\EcritureRepository;
use App\Service\FlashService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dossier', name: 'app_dossier_')]
class DossierController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request, RequestStack $stack, DossierRepository $repo): Response
    {
        $form = $this->createForm(DossierForm::class);

        // traite le formulaire si requête asynchrone
        if ($request->isXmlHttpRequest()) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $dossier = $form->getData();
                $dossier = $repo->createOrUpdate($dossier);

                if ($dossier) {
                    $this->addFlash(FlashService::TYPE_SUCCESS, "Le dossier a été créé.");
                    return new JsonResponse(["status" => 1, "url" => $this->generateUrl("app_dossier_detail", ["uuid" => $dossier->getUuid()])]);
                } else {
                    $this->addFlash(FlashService::TYPE_ERROR, "Erreur lors de l'écriture dans la base de données");
                    return $this->redirectToRoute("app_home");
                }
            }

            $responseBody = $this->renderForm('_forms/dossier_form.html.twig', [
                'form' => $form
            ]);

            return new JsonResponse(["status" => 0, "body" => $responseBody->getContent()]);
        } else {

            // si requête directe non asynchrone
            // sert le formulaire à remplir seulement si demandé par un appel controller
            if ($stack->getParentRequest() !== null) {

                return $this->renderForm('_forms/dossier_form.html.twig', [
                    "form" => $form
                ]);
            }

            return $this->redirectToRoute("app_home");
        }
    }


    #[Route('/{uuid}', name: 'detail')]
    public function details(string $uuid, DossierRepository $repo): Response
    {
        if(!$repo->selectOneDossier($uuid)) {
            $this->addFlash(FlashService::TYPE_WARNING, "Le dossier recherché n'existe pas.");
            
            return $this->redirectToRoute("app_home");

        } else {
            $ecritures = $repo->selectEcrituresFromDossier($uuid);
        }

        return $this->render("dossier/detail.html.twig", [
            "dossier_uuid" => $uuid,
            "ecritures" => $ecritures
        ]);
    }

    #[Route('/{uuid}/add-ecriture', name: 'ecriture_create')]
    public function ecritureCreate(string $uuid, DossierRepository $dossierRepo, Request $request, RequestStack $stack, EcritureRepository $ecritureRepo): Response
    {
        $form = $this->createForm(EcritureForm::class);

        // traite le formulaire si requête asynchrone
        if ($request->isXmlHttpRequest()) {

            $form->handleRequest($request);

            $dossier = $dossierRepo->selectOneDossier($uuid);

            // on revérifie si le dossier existe avant de créer une écriture dedans.
            if (empty($dossier)) {
                $this->addFlash(FlashService::TYPE_ERROR, "Le dossier associé est introuvable.");

                return new JsonResponse(["status" => 1, "url" => $this->generateUrl("app_home")]);
            }

            if ($form->isSubmitted() && $form->isValid()) {

                $ecriture = $form->getData();
                $ecriture->setDossierUuid($uuid);
                $ecriture = $ecritureRepo->createOrUpdate($ecriture);

                if ($ecriture) {
                    $this->addFlash(FlashService::TYPE_SUCCESS, "L'écriture a été créée.");
                    return new JsonResponse(["status" => 1, "url" => $this->generateUrl("app_dossier_detail", ["uuid" => $uuid])]);
                } else {
                    $this->addFlash(FlashService::TYPE_ERROR, "Erreur lors de l'écriture dans la base de données");
                    return $this->redirectToRoute("app_home");
                }
            }

            $responseBody = $this->renderForm('_forms/ecriture_form.html.twig', [
                'form' => $form,
                "dossier_uuid" => $uuid
            ]);

            return new JsonResponse(["status" => 0, "body" => $responseBody->getContent()]);
        } else {

            // si requête directe non asynchrone
            // sert le formulaire à remplir seulement si demandé par un appel controller
            if ($stack->getParentRequest() !== null) {

                return $this->renderForm('_forms/ecriture_form.html.twig', [
                    "form" => $form,
                    "dossier_uuid" => $uuid
                ]);
            }

            return $this->redirectToRoute("app_dossier_detail", ["uuid" => $uuid]);
        }
    }
}
