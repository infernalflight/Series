<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry,
         private EntityManagerInterface $em,
         private ContainerBag $parameterBag
    )
    {
        parent::__construct($registry, Serie::class);
    }

    public function findBestSeries(int $popularity): array
    {

        /**
         * En DQL
         *
        $dql = "SELECT s FROM App\Entity\Serie AS s
            WHERE s.popularity > :popularity";

        return $this->em->createQuery($dql)
            ->setParameter(':popularity', $popularity)
            ->execute();
        **/

        $q = $this->createQueryBuilder('s');

            $q->addOrderBy("s.popularity", "DESC")
            ->addOrderBy("s.vote", "DESC")
            ->andWhere("s.popularity > :popularity")
            ->setParameter(':popularity', $popularity);

            $expr = $q->expr();

            $cond1 = $expr->between('s.vote', ':min', ':max');
            $cond2 = $expr->like('s.name', ':name');

            $q->andWhere($expr->orX($cond1, $cond2))
            ->setParameter(':min', 3)
            ->setParameter(':max', 25)
            ->setParameter(':name', 'e');

            return $q->getQuery()
            ->getResult();

    }

    public function findSeriesWithPagination(int $page = 1)
    {

        $limit = $this->parameterBag->get('video_nombre_par_page');

        $qb = $this->createQueryBuilder('s')
            ->addOrderBy("s.popularity", "DESC")
            ->setMaxResults($limit);

        $offset = $limit * ($page) -1;

        $qb->setFirstResult($offset);

        $query = $qb->getQuery();

        return new Paginator($query);
    }


//    /**
//     * @return Serie[] Returns an array of Serie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
