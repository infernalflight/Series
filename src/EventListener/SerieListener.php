<?php

namespace App\EventListener;

use App\Entity\Serie;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SerieListener
{

    public function __construct(private ParameterBagInterface $parameterBag) {}

    // Exemple d'un listener qui agit lors d'une mise à jour

    public function preUpdate(Serie $serie): void
    {
        if (strlen($serie->getName()) > 10) {
            $serie->setOverview('Ce nom est trop long: '. $serie->getOverview());
        }
    }

    // EventListener qui se charge de supprimer les images liées à l'entité lors de la suppression de celle-ci

    public function preRemove(Serie $serie): void
    {
        if ($serie->getBackdrop()) {
            if (file_exists($this->parameterBag->get('backdrop_dir').$serie->getBackdrop())) {
                unlink($this->parameterBag->get('backdrop_dir').$serie->getBackdrop());
            }
        }

        // TODO faire ça aussi pour les posters

        // TODO faire un Listener équivalent pour les images de l'entité Season (liée en cascade à celle-ci)
    }

}