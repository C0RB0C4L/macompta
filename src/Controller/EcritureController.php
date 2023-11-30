<?php

namespace App\Controller;

use App\Form\EcritureForm;
use App\Repository\EcritureRepository;
use App\Service\FlashService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ecriture', name: 'app_ecriture_')]
class EcritureController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/{uuid}', name: 'edit_ajax')]
    public function edit(string $uuid, EcritureRepository $repo, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {

            $ecriture = $repo->selectOneEcriture($uuid);

            if ($ecriture) {
                $form = $this->createForm(EcritureForm::class, $ecriture, ["edit" => true]);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $ecriture = $form->getData();
                    $repo->createOrUpdate($ecriture);

                    $this->addFlash(FlashService::TYPE_SUCCESS, "L'écriture a été modifiée");

                    return new JsonResponse(["status" => 1, "url" => $this->generateUrl("app_home")]);
                }

                $responseBody = $this->renderForm('_forms/ecriture_edit_form.html.twig', [
                    "form" => $form,
                ]);

                return new JsonResponse(["status" => 0, "body" => $responseBody->getContent()]);
            }
        }

        return $this->redirectToRoute("app_home");
    }

    #[Route('/{uuid}/delete', name: 'delete')]
    public function delete(string $uuid, EcritureRepository $repo): Response
    {
        $delete = $repo->deleteEcriture($uuid);

        if ($delete) {
            $this->addFlash(FlashService::TYPE_SUCCESS, "L'écriture a bien été supprimée.");
        } else {
            $this->addFlash(FlashService::TYPE_ERROR, "L'écriture n'a pas pu être supprimée.");
        }

        return $this->redirectToRoute("app_home");
    }
}
