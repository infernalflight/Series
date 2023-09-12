<?php

namespace App\Controller;

use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/series', name: 'serie')]
class SerieController extends AbstractController
{

    #[Route('/', name: '_list')]
    public function list(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findBestSeries(120);

        //TODO Requeter les Séries en DB
        return $this->render('serie/list.html.twig', [
            'series' => $series
        ]);
    }

    #[Route('/show/{id}', name: '_show', requirements: ['id' => '\d+'])]
    public function details(SerieRepository $serieRepository, int $id): Response
    {
        $serie = $serieRepository->find($id);

        dd($serie);

        //TODO Requeter la Serie en DB
        return $this->render('serie/details.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(): Response
    {
        return $this->render('serie/create.html.twig');
    }


}
