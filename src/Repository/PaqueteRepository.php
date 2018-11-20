<?php

namespace App\Repository;

use App\Entity\Paquete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Paquete|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paquete|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paquete[]    findAll()
 * @method Paquete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaqueteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Paquete::class);
    }

//    /**
//    * @return Paquete[] Returns an array of Paquete objects
//    */
//    public function findByAdvancedSearch($lugar,$precio,$cant,$selectorP)
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.municipio LIKE :lugar OR
//            p.provincia LIKE :lugar OR
//            p.nombre LIKE :lugar OR
//            p.name LIKE :lugar OR
//            p.descripcion LIKE :lugar OR
//            p.description LIKE :lugar OR'
//            )->andWhere('p.:selectorP*:cant <= :precio')
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->setParameters([
//                'selectorP'=>$selectorP,
//                'lugar'=>$lugar,
//                'precio'=>$precio,
//                'cant' =>$cant
//            ])
//            ->getQuery()
//            ->getResult()
//        ;
//    }



//    public function findOneBySomeField($value): ?Paquete
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
