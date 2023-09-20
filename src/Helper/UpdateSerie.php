<?php

namespace App\Helper;

use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateSerie
{

    public function __construct(private SerieRepository $serieRepository, private EntityManagerInterface $em) {}

    public function removeOldSeries(string $genre = null, bool $force = false): int
    {
        $q = $this->serieRepository->createQueryBuilder('s');
        $q->andWhere($q->expr()->lt('s.lastAirDate', ':date'))
            ->setParameter(':date', new \DateTime('- 6 year'));
        if ($genre) {
            $q->andWhere($q->expr()->like('s.genres', ':genre'))
                ->setParameter(':genre', '%'.$genre.'%');
        }
        $results = $q->getQuery()->getResult();

        $cpt = 0;

        if ($force) {
            foreach($results as $serie) {
                $this->em->remove($serie);
                $cpt++;
            }
            $this->em->flush();
            return $cpt;
        }

        return count($results);
    }

}