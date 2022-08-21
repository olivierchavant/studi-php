<?php

namespace App\Repository;

use App\Entity\CandidatsAnnonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CandidatsAnnonces>
 *
 * @method CandidatsAnnonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidatsAnnonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidatsAnnonces[]    findAll()
 * @method CandidatsAnnonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatsAnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CandidatsAnnonces::class);
    }

    public function add(CandidatsAnnonces $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CandidatsAnnonces $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CandidatsAnnonces[] Returns an array of CandidatsAnnonces objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CandidatsAnnonces
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
