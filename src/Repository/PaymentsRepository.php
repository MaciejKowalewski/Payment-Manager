<?php

namespace App\Repository;

use App\Entity\Payments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payments>
 */
class PaymentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payments::class);
    }

    public function findByIdAndYear(int $email_id, string $payment_date): array{

        $payment_date = date('Y-m-d',strtotime($payment_date.'-12-31'));

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM payments WHERE email_id = :email_id AND payment_date < :payment_date';

        $resultSet = $conn->executeQuery($sql, [
            'email_id' => $email_id,
            'payment_date' => $payment_date
        ]);

        $paymentsList = [];
        //$normalizer = new GetSetMethodNormalizer();

        foreach($resultSet->fetchAllAssociative() as $array){
            foreach($array as $arrayPayment){
                //$payment = $normalizer->denormalize(Payments::class, $arrayPayment);
            }
        }

        return $paymentsList;
    }

    //    /**
    //     * @return Payments[] Returns an array of Payments objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Payments
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
