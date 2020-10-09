<?php

namespace App\Repository;

use App\Entity\Ward;
use Doctrine\ORM\EntityRepository;



class WardRepository  extends EntityRepository
{

    public function findAll()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Ward", "ASC");
       $wards =  $qb->getQuery()->getResult();
       return $wards;
    }



    public function findone($wdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.WardId = :wdid");
       $qb->setParameter('wdid', $wdid);
       $qb->orderBy("p.WardId", "ASC");
       $ward =  $qb->getQuery()->getOneOrNullResult();
       return $ward;
    }





}
