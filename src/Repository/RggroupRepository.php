<?php

namespace App\Repository;

use App\Entity\Rggroup;
use Doctrine\ORM\EntityRepository;



class RggroupRepository  extends EntityRepository
{

     public function findAll()
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select w.*, sum(rg.households) as total from rggroup as w left join roadgroup as rg on rg.rggroupid= w.rggroupid group by w.rggroupid';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $groups= $stmt->fetchAll();
        return $groups;
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
