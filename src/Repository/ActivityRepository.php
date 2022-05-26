<?php

namespace App\Repository;

use App\Entity\Activity;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em, )
    {
        parent::__construct($registry, Activity::class);
    }

    public function findActivitiesByPeriod(int $gap, dateTime $date)
    {
    $dateToString = date_format($date, 'Y-m-d H:i:s');
    
    $period = $date->modify(sprintf('-%d day', $gap));
    
    $strPeriod = date_format($period, 'Y-m-d H:i:s');

        try{
            $qb = $this->createQueryBuilder('a')
                        ->where("a.createdAt BETWEEN '$strPeriod' AND '$dateToString'")
                    ;
            
            return $qb->getQuery()->getResult();
            }
        catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}
