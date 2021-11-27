<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\RoundtoRoadgroup;


class RoundtoRoadgroupRepository  extends EntityRepository
{


    public function findone($rnid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.RoundId = :rnid");
       $qb->setParameter('rnid', $rnid);;
       $Roundlink =  $qb->getQuery()->getOneOrNullResult();
       return $Roundlink;
    }

    public function findRoadgroups($rndid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.RoundId = :rndid");
       $qb->setParameter('rndid', $rndid);
       $qb->orderBy("p.RoadgroupId", "ASC");
       $roadgroups =  $qb->getQuery()->getResult();
       return $roadgroups;
    }

    public function findRtoR($dvyid,$rndid,$rgid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.RoundId = :rndid");
       $qb->setParameter('rndid', $rndid);
        $qb->andwhere("p.DeliveryId = :dvyid");
       $qb->setParameter('dvyid', $dvyid);;
        $qb->andwhere("p.RoadgroupId = :rgid");
       $qb->setParameter('rgid', $rgid);;
       $Roundlink =  $qb->getQuery()->getOneOrNullResult();
       return $Roundlink;
    }




}
