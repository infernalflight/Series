<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(HttpClientInterface $httpClient): Response
    {
        $response = $httpClient->request('GET', 'https://api.chucknorris.io/jokes/random', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json'
            ]
        ]);

        $joke = json_decode($response->getContent(), true)['value'] ?? '';

        return $this->render('home/index.html.twig', [
            'joke' => $joke
        ]);
    }
}
