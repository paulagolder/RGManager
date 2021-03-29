<?php

namespace App\Repository;

use App\Entity\Delivery;
use Doctrine\ORM\EntityRepository;

use App\Entity\DeliverytoRoadgroup;


class DeliverytoRoadgroupRepository  extends EntityRepository
{




    public function findone($drid,$rgid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.DeliveryId = :drid");
       $qb->setParameter('drid', $drid);
       $qb->andwhere("p.RoadgroupId = :rgid");
       $qb->setParameter('rgid', $rgid);
       $Deliverylink =  $qb->getQuery()->getOneOrNullResult();
       return $Deliverylink;
    }

    public function findRoadgroups($drid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.DeliveryId = :drid");
       $qb->setParameter('drid', $drid);
       $qb->orderBy("p.RoadgroupId", "ASC");
       $Deliverys =  $qb->getQuery()->getResult();
       return $Deliverys;
    }






}
