<?php

namespace App\Repository;

use App\Entity\Subward;
use Doctrine\ORM\EntityRepository;



class SubwardRepository  extends EntityRepository
{


    public function findOne($swdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.SubwardId = :swdid");
       $qb->setParameter('swdid', $swdid);
       $qb->orderBy("p.SubwardId", "ASC");
       $subward =  $qb->getQuery()->getOneOrNullResult();
       return $subward;
    }

    public function findAll()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.SubwardId", "ASC");
       $subwards =  $qb->getQuery()->getResult();
       return $subwards;
    }



    public function findChildren($wdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.WardId = :wdid");
       $qb->setParameter('wdid', $wdid);
       $qb->orderBy("p.SubwardId", "ASC");
       $subwards =  $qb->getQuery()->getResult();
       return $subwards;
    }


}
