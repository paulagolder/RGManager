<?php

namespace App\Repository;

use App\Entity\Rggroup;
use Doctrine\ORM\EntityRepository;



class RggroupRepository  extends EntityRepository
{


    public function findAll()
    {
       $qb = $this->createQueryBuilder("p");
       $rggroups =  $qb->getQuery()->getResult();
       return $rggroups;
    }

    public function findone($wdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.Rggroupid = :wdid");
       $qb->setParameter('wdid', $wdid);
       $qb->orderBy("p.Rggroupid", "ASC");
       $group =  $qb->getQuery()->getOneOrNullResult();
       return $group;
    }

}
