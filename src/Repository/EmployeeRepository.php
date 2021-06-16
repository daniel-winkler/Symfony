<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function findByTermStrict(string $term) {
        // QueryBuilder https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/reference/query-builder.html
        $queryBuilder = $this->createQueryBuilder('e'); // creteQueryBuilder necesita un alias. por costumbre se pone la primera letra en minuscula de la entidad correspondiente, en este caso Employee
        // SELECT * FROM employee;
        
        $queryBuilder->where('e.name = :term');
        // SELECT * FROM employee e WHERE e.name = :term;

        $queryBuilder->orWhere('e.email = :term');
        // SELECT * FROM employee e WHERE e.name = :term OR e.email = :term

        $queryBuilder->orWhere('e.city = :term');
        // SELECT * FROM employee e WHERE e.name = :term OR e.email = :term OR e.city = :term

        $queryBuilder->setParameter('term', $term);
        $queryBuilder->orderBy('e.id', 'ASC');
        // si $term = 'hola'
        // SELECT * FROM employee e WHERE e.name = :term OR e.email = :term OR e.city = :term ORDBER BY e.id ASC;

        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return $result;
    }

    public function findByTerm(string $term) {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder->where(
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('e.name', ':term'),
                $queryBuilder->expr()->like('e.email', ':term'),
                $queryBuilder->expr()->like('e.city', ':term'),
            )
        );
        $queryBuilder->setParameter('term', '%'.$term.'%'); // % hace que en SQL busque el term en cuanlquier parte de la columna (si contiene, empieza, termina, etc)
        $queryBuilder->orderBy('e.id', 'ASC');

        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return $result;
    }

    // /**
    //  * @return Employee[] Returns an array of Employee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Employee
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
