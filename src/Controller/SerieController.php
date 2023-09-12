<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/series', name: 'serie')]
class SerieController extends AbstractController
{

    #[Route('/', name: '_list')]
    public function list(): Response
    {
        //TODO Requeter les Séries en DB
        return $this->render('serie/list.html.twig', [

        ]);
    }

    #[Route('/details/{id}', name: '_details', requirements: ['id' => '\d+'])]
    public function details(int $id): Response
     {
        //TODO Requeter la Serie en DB
        return $this->render('serie/details.html.twig');
    }

    #[Route('/create', name: '_create')]
    public function create(): Response
    {
        return $this->render('serie/create.html.twig');
    }


}
