<?php

namespace App\Repository;

use App\Entity\Roadgroup;
use Doctrine\ORM\EntityRepository;


class RoadgroupRepository extends EntityRepository
{

     public function findAll()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.RoadgroupId", "ASC");
       $roadgroups =  $qb->getQuery()->getResult();
       return $roadgroups;
    }


     public function findOne($rgid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.RoadgroupId = :rgid");
       $qb->setParameter('rgid', $rgid);
       $roadgroup =  $qb->getQuery()->getOneOrNullResult();;
       return $roadgroup;
    }

    public function findChildren($swdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.SubwardId = :swdid");
       $qb->setParameter('swdid', $swdid);
       $roadgroups =  $qb->getQuery()->getResult();
       return $roadgroups;
    }





      public function findLooseRoadgroups()
     {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'SELECT r.roadgroupid,r.name  FROM roadgroup as r left join ward as w on r.wardid = w.wardid where w.wardid is null';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll();
        return $roadgroups;
     }
}
