<?php

namespace App\Repository;

use App\Entity\Roadgrouptostreet;
use Doctrine\ORM\EntityRepository;



class RoadgrouptostreetRepository  extends EntityRepository
{

     public function remove($rgid,$stname,$stpart, $year)
     {
        $conn = $this->getEntityManager()->getConnection();
         $sql = 'Delete from  roadgrouptostreet   WHERE roadgroupid = "'.$rgid.'" and street= "'.$stname.'" and part= "'.$stpart.'" and year="'.$year.'";' ;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
     }

      public function getRoadgroup($stname,$stpart,$year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'Select * from  roadgrouptostreet   WHERE  street= "'.$stname.'" and part= "'.$stpart.'" and year="'.$year.'";';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pd= $stmt->fetchAll();
    //       $pd =  $stmt->getOneOrNullResult();
       return $pd;

     }


      public function countStreets($year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'Select rs.roadgroupid, count(*) as nos from  roadgrouptostreet rs  WHERE year = "'.$year.'" group by rs.roadgroupid  order by rs.roadgroupid;' ;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pd= $stmt->fetchAll();
       return $pd;

     }

       public function countPDs($year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'Select rs.roadgroupid, count(DISTINCT s.pd) as nos from  roadgrouptostreet rs left join street s on rs.street = s.name and (s.part = rs.part or (rs.part is null  and s.part = "" ) or (s.part is null  and rs.part = "" ) ) WHERE year = "'.$year.'" group by rs.roadgroupid  order by rs.roadgroupid;' ;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pdres= $stmt->fetchAll();
        $pds = [];
        foreach($pdres  as $pdrow)
        {
          $pds[$pdrow["roadgroupid"]]= $pdrow["nos"];
        }
       return $pds;

     }


     public function getYears()
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'Select rs.year as year from  roadgrouptostreet rs  WHERE 1 group by rs.year  order by rs.year desc;' ;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pd= $stmt->fetchAll();
       return $pd;

     }
}
