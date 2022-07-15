<?php

namespace App\Repository;

use App\Entity\Event;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findByFormation(string $sector, string $formation): array
    {
        $em = $this->getEntityManager();
        $sector = str_replace("-", " ", $sector);
        $formation = str_replace("-", " ", $formation);

        $query = $em->createQuery(
            'SELECT e
             FROM App\Entity\Event e 
             WHERE e.sector = :sector AND e.formation = :formation
            '
        )->setParameters(['sector' => $sector, 'formation' => $formation]);

        return $query->getResult();
    }

    public function findByTeacherAndDates(string $teacher, DateTime $start, DateTime $end): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT e
             FROM App\Entity\Event e 
             WHERE e.teacher = :teacher
             AND ((e.start_at >= :start AND e.start_at <= :end) 
             OR (e.end_at >= :start AND e.end_at <= :end))
            '
        )->setParameters(['teacher' => $teacher, 'start' => $start, 'end' => $end]);

        return $query->getResult();
    }



    //    /**
    //     * @return Event[] Returns an array of Event objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
