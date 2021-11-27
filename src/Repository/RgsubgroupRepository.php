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

       public function xfindChildren($wdid)
     {
        $conn = $this->getEntityManager()->getConnection();
     //   $sql = 'select sw.*, sum(rg.households) as total from rgsubgroup as sw left join roadgroup as rg on rg.rgsubgroupid = sw.rgsubgroupid where sw.rggroupid ="'.$wdid.'" group by sw.rgsubgroupid';
              $sql = 'select sw.*, sum(rg.households) as total from rgsubgroup as sw left join roadgroup as rg on rg.rgsubgroupid = sw.rgsubgroupid where sw.rggroupid ="'.$wdid.'" group by sw.rgsubgroupid';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $subgroups= $stmt->fetchAll();
        return $subgroups;
     }



}
