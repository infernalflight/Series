<?php

namespace App\EventListener;

use App\Entity\Serie;

class SerieListener
{

    public function preUpdate(Serie $serie): void
    {
        if (strlen($serie->getName()) > 10) {
            $serie->setOverview('Ce nom est trop long: '. $serie->getOverview());
        }
    }

}