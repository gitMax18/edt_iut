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

    public function findAllJoinByTeacherAndCourse(): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT e, t.firstname as teacher, c.name as course
            FROM App\Entity\Event e
            INNER JOIN e.teacher t
            INNER JOIN e.course c'
        );

        return $query->getResult();
    }


    public function findAllByFormation(int $formationId): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT e, c
             FROM App\Entity\Event e
             INNER JOIN e.course c
             WHERE e.formation = :formationId
            '
        )->setParameters(['formationId' => $formationId]);

        return $query->getResult();
    }

    public function findByTeacherAndDates(string $teacherId, DateTime $start, DateTime $end): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT e,t.firstname as teacher,c.name as course
             FROM App\Entity\Event e 
             INNER JOIN e.teacher t
             INNER JOIN e.course c
             WHERE t.id = :teacherId
             AND ((e.startAt >= :start AND e.startAt <= :end) 
             OR (e.endAt >= :start AND e.endAt <= :end))
            '
        )->setParameters(['teacherId' => $teacherId, 'start' => $start, 'end' => $end]);

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
