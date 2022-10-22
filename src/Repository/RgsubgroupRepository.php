<?php

namespace App\Repository;

use App\Entity\Rgsubgroup;
use Doctrine\ORM\EntityRepository;



class RgsubgroupRepository  extends EntityRepository
{

    public function findOne($swdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.Rgsubgroupid = :swdid");
       $qb->setParameter('swdid', $swdid);
       $subgroup =  $qb->getQuery()->getOneOrNullResult();
       return $subgroup;
    }

    public function findAll()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Rgsubgroupid", "ASC");
       $subgroups =  $qb->getQuery()->getResult();
       return $subgroups;
    }

    public function findChildren($wdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.Rggroupid = :wdid");
       $qb->setParameter('wdid', $wdid);
       $qb->orderBy("p.Rgsubgroupid", "ASC");
       $subgroups =  $qb->getQuery()->getResult();
       return $subgroups;
    }


}
