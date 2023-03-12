<?php

namespace App\Repository;

use App\Entity\Delivery;
use App\Entity\DeliverytoRoadgroup;
use Doctrine\ORM\EntityRepository;

class DeliveryRepository  extends EntityRepository
{

  public function findone($drid)
  {
    $qb = $this->createQueryBuilder("p");
    $qb->where("p.DeliveryId = :drid");
    $qb->setParameter('drid', $drid);
    $qb->orderBy("p.DeliveryId", "ASC");
    $Delivery =  $qb->getQuery()->getOneOrNullResult();
    return $Delivery;
  }

  public function findall()
  {
    $qb = $this->createQueryBuilder("p");
    $qb->orderBy("p.Name", "ASC");
    $Deliverys =  $qb->getQuery()->getResult();
    return $Deliverys;
  }

  public function findcurrent()
  {
    $qb = $this->createQueryBuilder("p");
    $qb->orderBy("p.CreateDate", "DESC");
    $Deliverys =  $qb->getQuery()->getResult();
    return $Deliverys;
  }

  public function findCandidateDeliveryRoadgroups($delivery,$year)
  {
    $conn = $this->getEntityManager()->getConnection();
    $sqlpd = 'select p.pdid  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pdid and s.year = "'.$year.'" and s.district = "'.$delivery->getDistrictId().'" order by s.seat ';
    if($delivery->getSeatIds())
      $sqlpd .= ' and s.seat = "'.$delivery->getSeatIds().'"';
    $stmt = $conn->prepare($sqlpd);
    $stmt->execute();
    $pollingdistricts = $stmt->fetchAll();
    $pdlist = "";
    foreach($pollingdistricts  as $key => $value)
    {
      $pdlist  .= '"'.$value["pdid"].'", ';
    }
    $pdlist .= '"xx"';
    $sql = 'select r.* from roadgroup as r where r.roadgroupid in (SELECT rs.roadgroupid FROM `roadgrouptostreet` as rs , street as s where rs.streetid = s.seq and  s.pdid IN('.$pdlist.')   and rs.year = "'.$year.'") and r.year = "'.$year.'"';
    $stmt = $conn->executeQuery($sql);

    $roadgroups= $stmt->fetchAll();
   // $roadgroups= $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
dump($roadgroups);
    $rglist=[];
    foreach($roadgroups as $rg)
    {
      $rglist[$rg["roadgroupid"]]= $rg;
    }
    return $rglist;
  }


  public function findDeliveryRoadgroups($delivery,$year)
  {

    $conn = $this->getEntityManager()->getConnection();
    $sqlpd = 'select p.pollingdistrictid  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pollingdistrictid and s.year = "'.$year.'" and s.district = "'.$delivery->getDistrictId().'" ';
    $stmt = $conn->prepare($sqlpd);
    $pollingdistricts = $stmt->fetchAll();
    $pdarray = array();
    $sql = 'select r.* from roadgroup as r where r.roadgroupid in ( SELECT rs.roadgroupid  FROM `roadgrouptostreet` as rs  join street as s on rs.streetid = s.seq  WHERE s.pdid IN(:pdlist)   and  rs.year = "2022") and r.year = "'.$year.'"';




    $stmt = $conn->executeQuery(
      $sql,
      [
      'pdlist' => $pdarray
      ],
      [
      'pdlist' => \Doctrine\DBAL\Connection::PARAM_STR_ARRAY
      ]
      );
      $roadgroups= $stmt->fetchAll();
  }




  public function findUnallocatedRoadgroups($dyid)
  {
    $conn = $this->getEntityManager()->getConnection();
    $sql = 'select r.* from roundtoroadgroup as r where  r.deliveryid = '.$dyid.'  and r.roundid = 0 ';
    $stmt = $conn->executeQuery($sql);
    $roadgroups= $stmt->fetchAll();
    return $roadgroups;
  }

  public function findAllocatedRoadgroups($dyid)
  {
    $conn = $this->getEntityManager()->getConnection();
    $sql = 'select r.* from roundtoroadgroup as r where  r.deliveryid = '.$dyid.'  and r.roundid > 0 ';
    $stmt = $conn->executeQuery($sql);
    $roadgroups= $stmt->fetchAll();
    return $roadgroups;
  }

  public function findRoadgroups($dvyid)
  {
    $conn = $this->getEntityManager()->getConnection();
    $sql = 'select rg.* from roundtoroadgroup as r , roadgroup as rg where  r.deliveryid = '.$dvyid.' and r.roadgroupid=rg.roadgroupid ';
    $stmt = $conn->executeQuery($sql);
    $roadgroups= $stmt->fetchAll();
    return $roadgroups;
  }

}
