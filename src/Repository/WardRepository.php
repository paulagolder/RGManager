<?php

namespace App\Repository;

use App\Entity\Ward;
use Doctrine\ORM\EntityRepository;



class WardRepository  extends EntityRepository
{

     public function findAll()
     {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select w.*, sum(rg.households) as total from ward as w left join roadgroup as rg on rg.wardid= w.wardid group by w.wardid';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $wards= $stmt->fetchAll();
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
