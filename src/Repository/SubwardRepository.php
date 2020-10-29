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



    public function xfindChildren($wdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.WardId = :wdid");
       $qb->setParameter('wdid', $wdid);
       $qb->orderBy("p.SubwardId", "ASC");
       $subwards =  $qb->getQuery()->getResult();
       return $subwards;
    }

       public function findChildren($wdid)
     {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select sw.*, sum(rg.households) as total from subward as sw left join roadgroup as rg on rg.subwardid = sw.subwardid where sw.wardid ="'.$wdid.'" group by sw.subwardid';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $subwards= $stmt->fetchAll();
        return $subwards;
     }



}
