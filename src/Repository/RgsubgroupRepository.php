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
       $qb->orderBy("p.Rgsubgroupid", "ASC");
       $subward =  $qb->getQuery()->getOneOrNullResult();
       return $subward;
    }

    public function findAll()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Rgsubgroupid", "ASC");
       $subwards =  $qb->getQuery()->getResult();
       return $subwards;
    }



    public function xfindChildren($wdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.WardId = :wdid");
       $qb->setParameter('wdid', $wdid);
       $qb->orderBy("p.rgsubgroupid", "ASC");
       $subwards =  $qb->getQuery()->getResult();
       return $subwards;
    }

       public function findChildren($wdid)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select sw.*, sum(rg.households) as total from rgsubgroup as sw left join roadgroup as rg on rg.rgsubgroupid = sw.rgsubgroupid where sw.rggroupid ="'.$wdid.'" group by sw.rgsubgroupid';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $subwards= $stmt->fetchAll();
        return $subwards;
     }



}
