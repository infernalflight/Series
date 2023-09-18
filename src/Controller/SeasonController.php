<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/season', name: 'season')]
class SeasonController extends AbstractController
{

    #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $season = new Season();
        $seasonForm = $this->createForm(SeasonType::class, $season);
        $seasonForm->handleRequest($request);

        if ($seasonForm->isSubmitted() && $seasonForm->isValid()) {
            $entityManager->persist($season);
            $entityManager->flush();

            $this->addFlash('success', 'La nouvelle saison est bien crée');

            $this->redirectToRoute('serie_show', ['id' => $season->getSerie()->getId()]);
        }

        return $this->render('season/edit.html.twig', [
           'form' => $seasonForm
        ]);

    }
}
