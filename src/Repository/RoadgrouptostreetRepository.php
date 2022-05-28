<?php

namespace App\Repository;

use App\Entity\Roadgrouptostreet;
use Doctrine\ORM\EntityRepository;



class RoadgrouptostreetRepository  extends EntityRepository
{

     public function remove($rgid,$rdseq, $year)
     {
        $conn = $this->getEntityManager()->getConnection();
         $sql = 'Delete from  roadgrouptostreet   WHERE roadgroupid = "'.$rgid.'" and streetid= "'.$rdseq.'" and year="'.$year.'";' ;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
     }

      public function getRoadgroup($rdid,$year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'Select rs.roadgroupid from  roadgrouptostreet  rs WHERE rs.streetid = "'.$rdid.'" and  rs.year="'.$year.'";';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res= $stmt->fetchAll();
        if(count($res) >0 )
            $rgid = $res[0]["roadgroupid"];
          else
            $rgid ="None";
       return $rgid;

     }


      public function findRg($rdid,$year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'Select rs.roadgroupid from  roadgrouptostreet  rs WHERE rs.streetid = "'.$rdid.'" and  rs.year="'.$year.'";';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rg= $stmt->fetch();
        if($rg)
            return $rg["roadgroupid"];
        else
            return 0;

     }

      public function countStreets($year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'Select rs.roadgroupid, count(*) as nos from  roadgrouptostreet rs  WHERE rs.year = "'.$year.'" group by rs.roadgroupid  order by rs.roadgroupid;' ;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pd= $stmt->fetchAll();
       return $pd;

     }

       public function countPDs($year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'Select rs.roadgroupid, count(DISTINCT s.pd) as nos from  roadgrouptostreet rs left join street s on rs.streetid = s.seq WHERE rs.year = "'.$year.'" group by rs.roadgroupid  order by rs.roadgroupid;' ;
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
