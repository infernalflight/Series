<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/series', name: 'serie')]
class SerieController extends AbstractController
{

    #[Route('/list/{page}', name: '_list', defaults: ['page' => 1])]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {
        //$series = $serieRepository->findBestSeries(60);

        $nbVideos = $this->getParameter('video_nombre_par_page');

        $series = $serieRepository->findSeriesWithPagination($page, $nbVideos);

        $maxPage = ceil($serieRepository->count([]) / $nbVideos);

        //TODO Requeter les Séries en DB
        return $this->render('serie/list.html.twig', [
            'series' => $series,
            'currentPage' => $page,
            'maxPage' => $maxPage
        ]);
    }

    #[Route('/show/{id}', name: '_show', requirements: ['id' => '\d+'])]
    public function details(SerieRepository $serieRepository, int $id): Response
    {
        $serie = $serieRepository->find($id);


        //TODO Requeter la Serie en DB
        return $this->render('serie/details.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            $em->persist($serie);
            $em->flush();

            $this->addFlash("success", "La Série est bien enregistrée");

            return $this->redirectToRoute('serie_list');
        }

        return $this->render('serie/edit.html.twig', [
            'form' => $serieForm
        ]);
    }

    #[Route('/edit/{id}', name: '_edit', requirements: ['id' => '\d+'])]
    public function edit(Serie $serie, Request $request, EntityManagerInterface $em): Response
    {
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            $em->persist($serie);
            $em->flush();

            $this->addFlash("success", "La Série est bien modifiée");

            return $this->redirectToRoute('serie_list');
        }

        return $this->render('serie/edit.html.twig', [
            'form' => $serieForm,
            'id' => $serie->getId()
        ]);
    }

    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '\d+'])]
    public function delete(Serie $serie, EntityManagerInterface $em): Response
    {
        $em->remove($serie);
        $em->flush();

        $this->addFlash("success", "La Série est bien supprimée");

        return $this->redirectToRoute('serie_list');

    }

}
